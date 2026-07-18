<?php

use App\Http\Controllers\BackEnd\AccountHeadController;
use App\Http\Controllers\BackEnd\AccountsController;
use App\Http\Controllers\BackEnd\AdminAuthController;
use App\Http\Controllers\BackEnd\BranchController;
use App\Http\Controllers\BackEnd\CategoryController;
use App\Http\Controllers\BackEnd\ChallanController;
use App\Http\Controllers\BackEnd\CompanyController;
use App\Http\Controllers\BackEnd\CompanyUserController;
use App\Http\Controllers\BackEnd\ContactController;
use App\Http\Controllers\BackEnd\DashboardController;
use App\Http\Controllers\BackEnd\IncomeCategoryController;
use App\Http\Controllers\BackEnd\IncomeController;
use App\Http\Controllers\BackEnd\IncomeReceiptController;
use App\Http\Controllers\BackEnd\PartyController;
use App\Http\Controllers\BackEnd\PaymentTypeController;
use App\Http\Controllers\BackEnd\PermissionController;
use App\Http\Controllers\BackEnd\ReceiptController;
use App\Http\Controllers\BackEnd\ReceiverController;
use App\Http\Controllers\BackEnd\ReportController;
use App\Http\Controllers\BackEnd\RoleController;
use App\Http\Controllers\BackEnd\SettingController;
use App\Http\Controllers\BackEnd\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/register', [AdminAuthController::class, 'registerForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'registerStore'])->name('admin.register.store');
    Route::get('/login', [AdminAuthController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/forgot-password', [AdminAuthController::class, 'forgotPasswordForm'])->name('admin.password.request');
    Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLink'])->name('admin.password.email');
    Route::get('/reset-password/{token}', [AdminAuthController::class, 'resetPasswordForm'])->name('admin.password.reset');
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.password.update');
});

Route::get('/admin', function () {
    return redirect()->route('dashboard.index');
});

Route::middleware(['auth', 'hasrole'])->prefix('admin')->group(function () {
    // Route::middleware(['auth'])->prefix('admin')->group(function () {
    // dashboard 
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // role route 
    Route::resource('roles', RoleController::class);

    // permission route 
    Route::resource('permissions', PermissionController::class);

    // user route 
    Route::resource('users', UserController::class);
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    // update profile route 
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [UserController::class, 'changePassword'])->name('profile.password');

    // contact route 
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::put('/contact/{contact}', [ContactController::class, 'update'])->name('contact.update');
    Route::delete('/contact/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy');

    // company route 
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    Route::put('/company/update/{company}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/company/delete/{company}', [CompanyController::class, 'destroy'])->name('company.destroy');

    // branch route 
    Route::get('/branch', [BranchController::class, 'index'])->name('branch.index');
    Route::post('/branch', [BranchController::class, 'store'])->name('branch.store');
    Route::put('/branch/{branch}', [BranchController::class, 'update'])->name('branch.update');
    Route::delete('/branch/{branch}', [BranchController::class, 'destroy'])->name('branch.destroy');

    // company user route 
    Route::get('/user', [CompanyUserController::class, 'index'])->name('user.index');
    Route::post('/user', [CompanyUserController::class, 'store'])->name('user.store');
    Route::put('/user/{user}', [CompanyUserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [CompanyUserController::class, 'destroy'])->name('user.destroy');

    // Accounts route
    Route::get('/accounts', [AccountsController::class, 'index'])->name('accounts.index');
    Route::post('/accounts/store', [AccountsController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{account}', [AccountsController::class, 'show'])->name('accounts.show');
    Route::put('/accounts/{account}', [AccountsController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{account}', [AccountsController::class, 'destroy'])->name('accounts.destroy');

    // payment type route 
    Route::get('/payment-type', [PaymentTypeController::class, 'index'])->name('payment-type.index');
    Route::post('/payment-type/store', [PaymentTypeController::class, 'store'])->name('payment-type.store');
    Route::put('/payment-type/update/{paymentType}', [PaymentTypeController::class, 'update'])->name('payment-type.update');
    Route::delete('/payment-type/delete/{paymentType}', [PaymentTypeController::class, 'destroy'])->name('payment-type.destroy');

    // party routes expense route 
    Route::get('/party', [PartyController::class, 'index'])->name('party.index');
    Route::post('/party', [PartyController::class, 'store'])->name('party.store');
    Route::put('/party/{party}', [PartyController::class, 'update'])->name('party.update');
    Route::delete('/party/{party}', [PartyController::class, 'destroy'])->name('party.destroy');

    // category route expense route
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // expense list route account head route description
    Route::get('/account-head', [AccountHeadController::class, 'index'])->name('account-head.index');
    Route::post('/account-head', [AccountHeadController::class, 'store'])->name('account-head.store');
    Route::put('/account-head/{accountHead}', [AccountHeadController::class, 'update'])->name('account-head.update');
    Route::delete('/account-head/{accountHead}', [AccountHeadController::class, 'destroy'])->name('account-head.destroy');

    // expense recipt route 
    Route::get('/receipt/expense', [ReceiptController::class, 'expenseIndex'])->name('receipt.expense.index');
    Route::get('/receipt/expense/create', [ReceiptController::class, 'expenseCreate'])->name('receipt.expense.create');
    Route::post('/receipt/store', [ReceiptController::class, 'store'])->name('receipt.store');
    Route::get('/receipt/{receipt}', [ReceiptController::class, 'show'])->name('receipt.show');
    Route::get('/receipt/{receipt}/edit', [ReceiptController::class, 'edit'])->name('receipt.edit');
    Route::put('/receipt/{receipt}', [ReceiptController::class, 'update'])->name('receipt.update');
    Route::delete('/receipt/{receipt}', [ReceiptController::class, 'destroy'])->name('receipt.destroy');
    Route::post('/receipt/{receipt}/cancel', [ReceiptController::class, 'cancel'])->name('receipt.cancel');
    Route::post('/receipt/{receipt}/payment', [ReceiptController::class, 'paymentStore'])->name('receipt.payment.store');
    Route::get('/receipt/{receipt}/payments', [ReceiptController::class, 'paymentHistory'])->name('receipt.payment.history');
    Route::get('/receipt/{receipt}/print', [ReceiptController::class, 'print'])->name('receipt.print');
    Route::get('/receipt/{receipt}/pdf', [ReceiptController::class, 'pdf'])->name('receipt.pdf');
    Route::get('/ajax/branch/{branch}', [ReceiptController::class, 'branchInfo'])->name('ajax.branch');
    Route::get('/ajax/party/{party}', [ReceiptController::class, 'partyInfo'])->name('ajax.party');
    Route::get('/ajax/account-head/{category}', [ReceiptController::class, 'accountHeads'])->name('ajax.account-head');
    Route::get('/party/{party}/profile', [ReceiptController::class, 'profile'])->name('party.profile');
    Route::post('/party/{party}/due-payment', [ReceiptController::class, 'duePayment'])->name('party.due.payment');

    // income,sales or invoice route start 
    // party routes income receiver route customer
    Route::get('/receiver', [ReceiverController::class, 'index'])->name('receiver.index');
    Route::post('/receiver', [ReceiverController::class, 'store'])->name('receiver.store');
    Route::put('/receiver/{party}', [ReceiverController::class, 'update'])->name('receiver.update');
    Route::delete('/receiver/{party}', [ReceiverController::class, 'destroy'])->name('receiver.destroy');

    // category route income route
    Route::get('/income/category', [IncomeCategoryController::class, 'index'])->name('income.category.index');
    Route::post('/income/category', [IncomeCategoryController::class, 'store'])->name('income.category.store');
    Route::put('/income/category/{category}', [IncomeCategoryController::class, 'update'])->name('income.category.update');
    Route::delete('/income/category/{category}', [IncomeCategoryController::class, 'destroy'])->name('income.category.destroy');

    // income list route item
    Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
    Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
    Route::put('/income/{accountHead}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy');

    // income receipt route
    Route::get('/income/receipt', [IncomeReceiptController::class, 'index'])->name('income.receipt.index');
    Route::get('/income/receipt/income/create', [IncomeReceiptController::class, 'createIncome'])->name('income.receipt.create');
    Route::post('/income/receipt/store', [IncomeReceiptController::class, 'store'])->name('income.receipt.store');
    Route::get('/income/receipt/{receipt}', [IncomeReceiptController::class, 'show'])->name('income.receipt.show');
    Route::get('/income/receipt/{receipt}/edit', [IncomeReceiptController::class, 'edit'])->name('income.receipt.edit');
    Route::put('/income/receipt/{receipt}', [IncomeReceiptController::class, 'update'])->name('income.receipt.update');
    Route::post('/income/receipt/{receipt}/cancel', [IncomeReceiptController::class, 'cancel'])->name('income.receipt.cancel');
    Route::get('/income/party/{party}/profile', [IncomeReceiptController::class, 'profile'])->name('income.party.profile');
    Route::post('/income/party/{party}/due-payment', [ReceiptController::class, 'duePayment'])->name('income.party.due.payment');

    // report 
    Route::get('/dashboard/pdf', [ReportController::class, 'pdf'])->name('dashboard.pdf');
    Route::get('/dashboard/excel', [ReportController::class, 'excel'])->name('dashboard.excel');
    // income or invoice route end

    // income receipt route
    Route::get('/challan', [ChallanController::class, 'index'])->name('challan.index');
    Route::get('/challan/create', [ChallanController::class, 'createChallan'])->name('challan.create');
    Route::post('/challan/store', [ChallanController::class, 'store'])->name('challan.store');
    Route::get('/challan/{receipt}', [ChallanController::class, 'show'])->name('challan.show');
    Route::get('/challan/{receipt}/edit', [ChallanController::class, 'edit'])->name('challan.edit');
    Route::put('/challan/{receipt}', [ChallanController::class, 'update'])->name('challan.update');
    Route::post('/challan/{receipt}/cancel', [ChallanController::class, 'cancel'])->name('challan.cancel');
    Route::get('/challan/{receipt}/print', [ChallanController::class, 'print'])->name('challan.print');
    Route::get('/challan/{receipt}/pdf', [ChallanController::class, 'pdf'])->name('challan.pdf');
});
