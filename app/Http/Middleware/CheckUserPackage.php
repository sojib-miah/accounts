<?php

namespace App\Http\Middleware;

use App\Models\CompanyPackage;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPackage
{
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super-Admin')) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | Find User Package
        |--------------------------------------------------------------------------
        |
        | First check user-specific package.
        | If there is no user package, check company package.
        |
        */

        $package = CompanyPackage::with('package')
            ->where(function ($query) use ($user) {

                $query->where('user_id', $user->id)

                    ->orWhere(function ($query) use ($user) {

                        $query->whereNull('user_id')
                            ->where('company_id', $user->company_id);
                    });
            })
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Determine Package Status
        |--------------------------------------------------------------------------
        */

        $packageStatus = 'No Package';

        if ($package) {

            /*
            |--------------------------------------------------------------------------
            | Cancelled
            |--------------------------------------------------------------------------
            */

            if ($package->status === 'Cancelled') {

                $packageStatus = 'Cancelled';
            }

            /*
            |--------------------------------------------------------------------------
            | Manually Expired
            |--------------------------------------------------------------------------
            */ elseif ($package->status === 'Expired') {

                $packageStatus = 'Expired';
            }

            /*
            |--------------------------------------------------------------------------
            | Expire Date Passed
            |--------------------------------------------------------------------------
            */ elseif (
                $package->expire_date &&
                Carbon::today()->greaterThan(
                    Carbon::parse($package->expire_date)
                )
            ) {

                $packageStatus = 'Expired';

                /*
                |--------------------------------------------------------------------------
                | Automatically update database
                |--------------------------------------------------------------------------
                */

                $package->update([
                    'status' => 'Expired',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Active
            |--------------------------------------------------------------------------
            */ elseif ($package->status === 'Active') {

                $packageStatus = 'Active';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Allow Active Package
        |--------------------------------------------------------------------------
        */

        if ($packageStatus === 'Active') {

            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard and Upgrade Routes
        |--------------------------------------------------------------------------
        |
        | These routes remain accessible even without a valid package.
        |
        */

        $allowedRoutes = [
            'dashboard.index',
            'upgrade.index',
        ];

        if (
            $request->routeIs($allowedRoutes)
        ) {

            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect To Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('dashboard.index')
            ->with([
                'package_restricted' => true,
                'package_status' => $packageStatus,
            ]);
    }
}
