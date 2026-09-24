<?php

use App\Http\Controllers\FrontEnd\ContactController;
use App\Http\Controllers\FrontEnd\HomeController;
use Illuminate\Support\Facades\Route;


// include route
require __DIR__ . '/admin.php';

// Route::get('/', function () {
//     return redirect()->route('dashboard.index');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

// frontend contact 
Route::get('/contact', [ContactController::class, 'index'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::delete('/contact/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy');
