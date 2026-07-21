<?php

use App\Models\CompanyPackage;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

function setting()
{
    return Setting::first();
}

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return ucfirst($formatter->format($number));
    }
}

class PackageHelper
{
    public static function package()
    {
        $user = Auth::user();

        return CompanyPackage::with('package')
            ->where(function ($query) use ($user) {
                $query->where('company_id', $user->company_id)
                    ->orWhere('user_id', $user->id);
            })
            ->where('status', 'Active')
            ->first();
    }
}
