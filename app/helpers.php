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
    protected static $package = null;
    public static function package()
    {
        if (self::$package === null) {
            self::$package = CompanyPackage::with('package')
                ->where('company_id', Auth::user()->company_id)
                ->where('status', 'Active')
                ->first();
        }

        return self::$package;
    }
}
