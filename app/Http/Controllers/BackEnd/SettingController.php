<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('BackEnd.Setting.setting', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image',
            'favaicon' => 'nullable|image',
            'currency' => 'required',
            'footer_text' => 'nullable',
        ]);

        $setting = Setting::first();

        $logo = $setting?->logo;
        $favicon = $setting?->favaicon;

        // Logo Upload
        if ($request->hasFile('logo')) {

            $logo = time() . '_logo.' .
                $request->logo->extension();

            $request->logo->move(
                public_path('uploads/settings'),
                $logo
            );
        }

        // Favicon Upload
        if ($request->hasFile('favaicon')) {

            $favicon = time() . '_favicon.' .
                $request->favaicon->extension();

            $request->favaicon->move(
                public_path('uploads/settings'),
                $favicon
            );
        }

        Setting::updateOrCreate(
            ['id' => $setting?->id ?? 1],
            [
                'logo' => $logo,
                'favaicon' => $favicon,
                'currency' => $request->currency,
                'footer_text' => $request->footer_text,
            ]
        );

        return back()->with(
            'success',
            'Settings Saved Successfully'
        );
    }
}
