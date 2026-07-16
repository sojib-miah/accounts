<?php

use App\Models\Setting;

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
