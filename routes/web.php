<?php

use App\Http\Controllers\FrontEnd\ContactController;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SSLCommerzController;
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

// sslcommerze route 
Route::middleware('auth')->group(function () {
    Route::post('/payment/sslcommerz/{package}', [SSLCommerzController::class, 'initiate'])->name('payment.sslcommerz.initiate');
});
Route::post('/payment/sslcommerz/success', [SSLCommerzController::class, 'success'])->name('payment.sslcommerz.success');
Route::post('/payment/sslcommerz/fail', [SSLCommerzController::class, 'fail'])->name('payment.sslcommerz.fail');
Route::post('/payment/sslcommerz/cancel', [SSLCommerzController::class, 'cancel'])->name('payment.sslcommerz.cancel');
Route::post('/payment/sslcommerz/ipn', [SSLCommerzController::class, 'ipn'])->name('payment.sslcommerz.ipn');
Route::get('/payment/result/{payment}', [SSLCommerzController::class, 'result'])->name('payment.result');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payment.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('admin.payment.show');
    Route::patch('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('admin.payment.update-status');
});
