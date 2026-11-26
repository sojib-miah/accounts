<?php

use App\Http\Controllers\BackEnd\ActivityLogController;
use App\Http\Controllers\BackEnd\UserImpersonationController;
use App\Http\Controllers\FrontEnd\ContactController;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SSLCommerzController;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Artisan;

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

// ssl route 
// 1 Gateway callbacks: {package} route er UPORE thakte hobe, session chara
Route::withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class,])->group(function () {
    Route::post('/payment/sslcommerz/success', [SSLCommerzController::class, 'success'])->name('payment.sslcommerz.success');
    Route::post('/payment/sslcommerz/fail', [SSLCommerzController::class, 'fail'])->name('payment.sslcommerz.fail');
    Route::post('/payment/sslcommerz/cancel', [SSLCommerzController::class, 'cancel'])->name('payment.sslcommerz.cancel');
    Route::post('/payment/sslcommerz/ipn', [SSLCommerzController::class, 'ipn'])->name('payment.sslcommerz.ipn');
});

// 2 Payment shuru: wildcard route, tai sobar niche
Route::middleware('auth')->group(function () {
    Route::post('/payment/sslcommerz/{package}', [SSLCommerzController::class, 'initiate'])->name('payment.sslcommerz.initiate');
    Route::get('/payment/result/{payment}', [SSLCommerzController::class, 'result'])->name('payment.result');
});


Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payment.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('admin.payment.show');
    Route::patch('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('admin.payment.update-status');
});

// cache clear route 
Route::get('cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return back()->with('success', 'Cache Cleared Successfull!');
})->name('cache.clear');

// admin login as user route
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/login-as-user', [UserImpersonationController::class, 'index'])->name('admin.login-as-user');
    Route::post('/login-as-user', [UserImpersonationController::class, 'login'])->name('admin.login-as-user.login');
    Route::post('/stop-impersonation', [UserImpersonationController::class, 'stop'])->name('admin.stop-impersonation');

    // activity log route
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-log.index');
});

// language route 
Route::post('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'bn'], true)) {
        abort(404);
    }
    Session::put('locale', $locale);
    return redirect()->back();
})->name('language.switch');