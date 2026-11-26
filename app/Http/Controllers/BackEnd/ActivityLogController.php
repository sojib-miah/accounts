<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
     public function index(Request $request)
    {
        $user = Auth::user();

        $query = Activity::with(['causer', 'subject'])
            ->latest('id');

        // Super Admin can see all activities.
        // Other users see activities belonging to their company.
        if (!$user->hasRole('Super-Admin')) {
            $query->where(
                'properties->company_id',
                $user->company_id
            );
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhereHas('causer', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id)
                ->where('causer_type', config('auth.providers.users.model'));
        }

        // Module filter
        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        // Event filter
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $activities = $query->paginate(20)->withQueryString();

        $usersQuery = User::query();

        if (!$user->hasRole('Super-Admin')) {
            $usersQuery->where('company_id', $user->company_id);
        }

        $users = $usersQuery->orderBy('name')->get();

        return view('BackEnd.ActivityLog.index', compact(
            'activities',
            'users'
        ));
    }
}
