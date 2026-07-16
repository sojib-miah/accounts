<?php

use App\Http\Controllers\FrontEnd\HomeController;
use Illuminate\Support\Facades\Route;


// include route
require __DIR__ . '/admin.php';

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

// Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/send-contact-message', [HomeController::class, 'store'])->name('contact.send');
