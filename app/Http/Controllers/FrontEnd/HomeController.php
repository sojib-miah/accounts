<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalVisitors = Visitor::count();

        $todayVisitors = Visitor::whereDate(
            'first_visited_at',
            Carbon::today()
        )->count();

        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('welcome', compact('totalVisitors', 'todayVisitors', 'packages'));
    }
}
