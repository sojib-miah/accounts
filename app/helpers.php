<?php

use App\Models\CompanyPackage;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('setting')) {
    function setting()
    {
        if (!Auth::check()) {
            return null;
        }

        return Setting::where('user_id', Auth::id())->first();
    }
}

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        return ucfirst($formatter->format($number));
    }
}

if (!class_exists('PackageHelper')) {
    class PackageHelper
    {
        public static function package()
        {
            $user = Auth::user();

            if (!$user) {
                return null;
            }

            return CompanyPackage::with('package')
                ->where(function ($query) use ($user) {
                    $query->where('company_id', $user->company_id)
                        ->orWhere('user_id', $user->id);
                })
                ->where('status', 'Active')
                ->whereHas('package', function ($q) {
                    $q->where('is_active', 1);
                })
                ->first();
        }

        public static function checkLimit($field, $currentCount)
        {
            $companyPackage = self::package();

            if (!$companyPackage) {
                return 'No active package assigned.';
            }

            if (!$companyPackage->package->is_active) {
                return 'Your package is inactive.';
            }

            $limit = $companyPackage->package->{$field};

            if ($limit != -1 && $currentCount >= $limit) {
                return ucfirst(str_replace('_', ' ', $field))
                    . ' exceeded.';
            }

            return null;
        }
    }
}
