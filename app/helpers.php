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
                ->where(function ($query) {
                    $query->whereNull('expire_date')
                        ->orWhereDate('expire_date', '>=', now()->toDateString());
                })
                ->whereHas('package', function ($query) {
                    $query->where('is_active', true);
                })->latest()->first();
        }

        /**
         * Check package limit
         */
        public static function checkLimit($field, $currentCount)
        {
            $companyPackage = self::package();

            if (!$companyPackage || !$companyPackage->package) {
                return 'No active package assigned.';
            }

            $limit = $companyPackage->package->{$field};

            // Unlimited support
            if ($limit === null || strtolower(trim((string) $limit)) === 'unlimited' || (string) $limit === '-1') {
                return null;
            }

            $limit = (int) $limit;

            if ($limit <= 0) {
                return ucfirst(str_replace('_', ' ', $field)) . ' limit is not configured.';
            }

            if ($currentCount >= $limit) {
                return ucfirst(str_replace('_', ' ', $field)) . ' limit exceeded. Your package allows maximum ' . $limit . '.';
            }

            return null;
        }
    }
}
