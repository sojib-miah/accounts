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
            ->whereHas('package', function ($q) {
                $q->where('is_active', 1)
                    ->whereDate('end_date', '>=', now());
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

        if (now()->gt($companyPackage->package->end_date)) {
            return 'Your package has expired.';
        }

        $limit = $companyPackage->package->{$field};

        if ($limit != -1 && $currentCount >= $limit) {
            return ucfirst(str_replace('_', ' ', $field)) . ' exceeded.';
        }

        return null;
    }
}
