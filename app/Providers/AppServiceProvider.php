<?php

namespace App\Providers;

use App\Listeners\AuthActivityListener;
use App\Models\Account;
use App\Models\AccountHead;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyPackage;
use App\Models\Contact;
use App\Models\CustomerCompany;
use App\Models\Package;
use App\Models\Party;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReceiptPayment;
use App\Models\SerialNumber;
use App\Models\Setting;
use App\Models\StockTransaction;
use App\Models\User;
use App\Observers\ActivityObserver;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Event::listen(Registered::class, [AuthActivityListener::class, 'registered']);
        Event::listen(Login::class, [AuthActivityListener::class, 'login']);
        Event::listen(Logout::class, [AuthActivityListener::class, 'logout']);
        Event::listen(Failed::class, [AuthActivityListener::class, 'failed']);

        $models = [
            Account::class,
            AccountHead::class,
            AccountTransaction::class,
            Branch::class,
            Brand::class,
            Category::class,
            Company::class,
            CompanyPackage::class,
            Contact::class,
            CustomerCompany::class,
            Package::class,
            Party::class,
            Payment::class,
            PaymentType::class,
            Product::class,
            Receipt::class,
            ReceiptItem::class,
            ReceiptPayment::class,
            SerialNumber::class,
            Setting::class,
            StockTransaction::class,
            User::class,
        ];

        foreach ($models as $model) {
            $model::observe(ActivityObserver::class);
        }
    }
}
