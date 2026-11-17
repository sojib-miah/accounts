<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
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

        return view('welcome', compact('totalVisitors', 'todayVisitors'));
    }
}
