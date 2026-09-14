<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class ProPackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('BackEnd.ProPackage.index', compact('packages'));
    }
}
