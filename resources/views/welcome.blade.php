<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BIMS</title>

        <link rel="icon" type="image/x-icon"
            href="{{ optional(setting())->favaicon ? asset('uploads/settings/' . setting()->favaicon) : asset('default-favicon.ico') }}" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" />
        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

        @include('FrontEnd.Home.style')

    </head>

    <body class="bg-light text-dark">

        @include('FrontEnd.Home.header')

        <main class="">
            <section class="hero-section">
                <div class="hero-blur-left"></div>
                <div class="hero-blur-right">
                </div>
                <div class="hero-container">
                    <div>
                        <span
                            class="inline-flex items-center rounded-full bg-cyan-100 px-4 py-2 text-sm font-semibold text-cyan-700">
                            Smart Company Management System
                        </span>
                        <h1 class="mt-6 text-4xl font-extrabold leading-tight text-slate-900 lg:text-4xl">
                            Manage Your Business Smarter with BIMS
                        </h1>
                        <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                            BIMS (Business Integration Management System) brings Purchase, Inventory, Sales, Accounting,
                            and Reporting together in one
                            platform—so you can streamline operations, stay in control, and turn everyday business data
                            into smarter decisions.
                        </p>
                        @if (Auth::user())
                            <div class="mt-10">
                                <a href="{{ route('dashboard.index') }}"
                                    class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all duration-300 shadow-sm hover:shadow">
                                    {{ __('common.go_to_dashboard') }}
                                </a>
                            </div>
                        @else
                            <div class="mt-10 flex flex-wrap gap-4">
                                <a href="{{ route('admin.login') }}"
                                    class="rounded-xl border border-slate-300 bg-cyan-600 px-6 py-2 text-lg text-white transition hover:shadow-lg">
                                    {{ __('common.get_started') }}
                                </a>
                                <a href="{{ route('admin.register') }}"
                                    class="rounded-xl border border-slate-300 bg-green-600 px-6 py-2 text-lg text-white transition hover:shadow-lg">
                                    {{ __('common.free_trial') }}
                                </a>
                                <a href="{{ route('admin.register') }}"
                                    class="rounded-xl border border-slate-300 bg-white px-6 py-2 text-lg transition hover:shadow-lg">
                                    {{ __('common.demo_request') }}
                                </a>
                            </div>
                        @endif
                        <div class="mt-14 flex flex-wrap gap-10">
                            <div>
                                <h3 class="text-3xl font-bold text-cyan-600">500+</h3>
                                <p class="text-slate-500">{{ __('common.companies') }}</p>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold text-cyan-600">10K+</h3>
                                <p class="text-slate-500">{{ __('common.users') }}</p>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold text-cyan-600">99.9%</h3>
                                <p class="text-slate-500">{{ __('common.uptime') }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- <div>
                        <img src="{{ asset('assets/img/bg-image.jpeg') }}" alt="bg omage">
                    </div> --}}

                    <div class="relative group">

                        {{-- Background Glow --}}
                        <div
                            class="absolute -inset-5 rounded-[2rem] bg-gradient-to-br from-cyan-200 via-blue-100 to-emerald-100 blur-2xl opacity-70">
                        </div>

                        {{-- Main Image Container --}}
                        <div
                            class="relative rounded-3xl border border-white/80 bg-white/70 p-2 sm:p-3 shadow-2xl shadow-slate-300/50 backdrop-blur-sm">

                            {{-- Image --}}
                            <div class="relative overflow-hidden rounded-2xl bg-slate-100">

                                <img src="{{ asset('assets/img/bg-image.jpeg') }}"
                                    alt="BIMS Business Management Dashboard"
                                    class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-[1.03]">

                                {{-- Image Overlay --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-tr from-slate-950/10 via-transparent to-white/10 pointer-events-none">
                                </div>

                            </div>

                        </div>

                        {{-- Floating Card: Inventory --}}
                        <div
                            class="absolute -left-3 sm:-left-8 top-10 sm:top-16 z-10 animate-[float_4s_ease-in-out_infinite]">

                            <div
                                class="flex items-center gap-3 rounded-xl border border-white bg-white/95 p-3 sm:p-4 shadow-xl shadow-slate-300/30 backdrop-blur-md">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                    <i class="fa-solid fa-boxes-stacked text-lg"></i>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-500">Inventory</p>
                                    <p class="text-sm font-bold text-slate-900">Stock Management</p>
                                </div>

                            </div>

                        </div>


                        {{-- Floating Card: Reports --}}
                        <div
                            class="absolute -right-2 sm:-right-7 bottom-12 sm:bottom-20 z-10 animate-[float_5s_ease-in-out_infinite]">

                            <div
                                class="flex items-center gap-3 rounded-xl border border-white bg-white/95 p-3 sm:p-4 shadow-xl shadow-slate-300/30 backdrop-blur-md">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-chart-line text-lg"></i>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-500">Business Reports</p>
                                    <p class="text-sm font-bold text-slate-900">Smart Analytics</p>
                                </div>

                            </div>

                        </div>

                        {{-- Bottom Badge --}}
                        <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 z-10 w-max">

                            <div
                                class="flex items-center gap-2 rounded-full border border-white bg-slate-900 px-5 py-3 text-white shadow-xl">

                                <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-400 animate-pulse"></span>

                                <span class="text-xs sm:text-sm font-semibold tracking-wide">
                                    Powered by BIMS Technology
                                </span>

                            </div>

                        </div>

                    </div>

                </div>
            </section>

            <section id="features" class="py-8 bg-white">
                <div class="sojib">
                    <div class="max-w-3xl mx-auto text-center">
                        <span
                            class="inline-block px-4 py-2 rounded-full bg-cyan-100 text-cyan-600 text-sm font-semibold">
                            {{ __('common.features') }}
                        </span>
                        <h2 class="mt-5 text-3xl font-extrabold text-slate-900">
                            Everything Your Business Needs
                        </h2>
                        <p class="mt-6 text-lg text-slate-600 leading-8">
                            BIMS brings all your essential business operations into
                            one secure and easy-to-use platform, helping your team
                            save time, improve productivity, and make better decisions.
                        </p>
                    </div>
                    <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">
                        <!-- Card 1 -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-5 text-center transition-all duration-500 hover:-translate-y-3 hover:border-cyan-300 hover:bg-gradient-to-br hover:from-cyan-50 hover:via-white hover:to-blue-50 hover:shadow-[0_20px_50px_rgba(6,182,212,.18)]">
                            <!-- Top Line -->
                            <div
                                class="absolute left-0 top-0 h-1 w-full scale-x-0 origin-left bg-gradient-to-r from-blue-600 to-cyan-500 transition-transform duration-500 group-hover:scale-x-100">
                            </div>
                            <!-- Glow -->
                            <div
                                class="absolute inset-0 rounded-2xl bg-gradient-to-br from-cyan-200/20 via-transparent to-blue-200/20 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-yellow-100 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-lg">
                                    <i class="fa-solid fa-cart-plus text-2xl text-green-600"></i>
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 transition group-hover:text-cyan-600">
                                    Product Purchase
                                </h3>
                                <p class="mt-4 leading-7 text-slate-600 transition group-hover:text-slate-700 text-sm">
                                    Manage suppliers, purchase orders, receiving, payments, and purchase history from
                                    one centralized platform.
                                </p>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-5 text-center transition-all duration-500 hover:-translate-y-3 hover:border-cyan-300 hover:bg-gradient-to-br hover:from-cyan-50 hover:via-white hover:to-blue-50 hover:shadow-[0_20px_50px_rgba(6,182,212,.18)]">
                            <div
                                class="absolute left-0 top-0 h-1 w-full scale-x-0 origin-left bg-gradient-to-r from-blue-600 to-cyan-500 transition-transform duration-500 group-hover:scale-x-100">
                            </div>
                            <div
                                class="absolute inset-0 rounded-sm bg-gradient-to-br from-cyan-200/20 via-transparent to-blue-200/20 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-green-100 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-lg">
                                    <i class="fa-solid fa-warehouse text-2xl text-green-600"></i>
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 transition group-hover:text-cyan-600">
                                    Inventory
                                </h3>
                                <p class="mt-4 leading-7 text-slate-600 transition group-hover:text-slate-700 text-sm">
                                    Track stock, products, warehouses, branches, transfers, and inventory movements with
                                    real-time visibility.
                                </p>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-5 text-center transition-all duration-500 hover:-translate-y-3 hover:border-cyan-300 hover:bg-gradient-to-br hover:from-cyan-50 hover:via-white hover:to-blue-50 hover:shadow-[0_20px_50px_rgba(6,182,212,.18)]">
                            <div
                                class="absolute left-0 top-0 h-1 w-full scale-x-0 origin-left bg-gradient-to-r from-blue-600 to-cyan-500 transition-transform duration-500 group-hover:scale-x-100">
                            </div>
                            <div
                                class="absolute inset-0 rounded-sm bg-gradient-to-br from-cyan-200/20 via-transparent to-blue-200/20 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-blue-100 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-lg">
                                    <i class="fa-solid fa-chart-line text-2xl text-blue-600"></i>
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 transition group-hover:text-cyan-600">
                                    Sales
                                </h3>
                                <p class="mt-4 leading-7 text-slate-600 transition group-hover:text-slate-700 text-sm">
                                    Manage sales orders, invoices, customers, payments, returns, and your complete sales
                                    operations.
                                </p>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-5 text-center transition-all duration-500 hover:-translate-y-3 hover:border-cyan-300 hover:bg-gradient-to-br hover:from-cyan-50 hover:via-white hover:to-blue-50 hover:shadow-[0_20px_50px_rgba(6,182,212,.18)]">
                            <div
                                class="absolute left-0 top-0 h-1 w-full scale-x-0 origin-left bg-gradient-to-r from-blue-600 to-cyan-500 transition-transform duration-500 group-hover:scale-x-100">
                            </div>
                            <div
                                class="absolute inset-0 rounded-sm bg-gradient-to-br from-cyan-200/20 via-transparent to-blue-200/20 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-purple-100 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-lg">
                                    <i class="fa-solid fa-calculator text-2xl text-purple-600"></i>
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 transition group-hover:text-cyan-600">
                                    Accounting
                                </h3>
                                <p class="mt-4 leading-7 text-slate-600 transition group-hover:text-slate-700 text-sm">
                                    Monitor income, expenses, accounts, payments, balances, and financial transactions
                                    with ease.
                                </p>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-5 text-center transition-all duration-500 hover:-translate-y-3 hover:border-cyan-300 hover:bg-gradient-to-br hover:from-cyan-50 hover:via-white hover:to-blue-50 hover:shadow-[0_20px_50px_rgba(6,182,212,.18)]">
                            <div
                                class="absolute left-0 top-0 h-1 w-full scale-x-0 origin-left bg-gradient-to-r from-blue-600 to-cyan-500 transition-transform duration-500 group-hover:scale-x-100">
                            </div>
                            <div
                                class="absolute inset-0 rounded-sm bg-gradient-to-br from-cyan-200/20 via-transparent to-blue-200/20 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-lime-100 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:shadow-lg">
                                    <i class="fa-solid fa-file-invoice text-2xl text-lime-600"></i>
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 transition group-hover:text-cyan-600">
                                    Business Reports
                                </h3>
                                <p class="mt-4 leading-7 text-slate-600 transition group-hover:text-slate-700 text-sm">
                                    Get clear insights into sales, purchases, inventory, expenses, profit, and overall
                                    business performance.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="partner-section">
                <div class="partner-container">
                    <div class="text-center mb-12">
                        <span class="inline-flex px-4 py-2 rounded-full bg-cyan-100 text-cyan-600 font-semibold">
                            {{ __('common.partners') }}
                        </span>
                        <h2 class="mt-5 text-3xl font-bold text-slate-900">
                            Our Partners
                        </h2>
                        <p class="mt-4 text-slate-600 text-lg max-w-2xl mx-auto">
                            Trusted by leading companies across different industries who rely on BIMS
                            to manage their business efficiently.
                        </p>
                    </div>
                    <div class="swiper partnerSlider logo-slider">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Agrey Handicraft logo.jpg') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>

                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Computer Point logo.jpg') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>

                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Enlive logo.jpg') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/HM electronics logo.jpg') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Khati Organic logo.jpg') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Khatir Choya logo.jpg') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Wisdom Electronics BD logo.jpg') }}"
                                        class="logo-image" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="{{ asset('assets/img/Yes Electronics logo.png') }}" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="pricing" class="py-20 bg-slate-50">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">

                    {{-- Section Header --}}
                    <div class="text-center max-w-2xl mx-auto mb-14">
                        <span
                            class="inline-flex items-center px-4 py-1.5 rounded-full bg-cyan-100 text-cyan-700 text-sm font-semibold tracking-wide uppercase">
                            {{ __('common.our_pricing') }}
                        </span>

                        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            Packages & Pricing
                        </h2>

                        <p class="mt-4 text-slate-600 text-base sm:text-lg">
                            Choose the perfect plan to manage and grow your business.
                            Simple pricing, powerful features.
                        </p>
                    </div>

                    {{-- Packages --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                        @forelse($packages as $package)

                            @php
                                $popular = $loop->iteration === 2;
                            @endphp

                            <div
                                class="relative flex flex-col bg-white rounded-2xl border
                    {{ $popular ? 'border-cyan-500 shadow-xl shadow-cyan-100 lg:scale-105 z-10' : 'border-slate-200 shadow-sm' }}
                    transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                                {{-- Popular Badge --}}
                                @if ($popular)
                                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                        <span
                                            class="inline-flex items-center gap-1 px-5 py-2 rounded-full bg-cyan-600 text-white text-xs font-bold uppercase tracking-wider shadow-lg">
                                            <i class="fas fa-star"></i>
                                            Most Popular
                                        </span>
                                    </div>
                                @endif

                                <div class="p-8 flex-1">

                                    {{-- Package Name --}}
                                    <h3 class="text-xl font-bold text-slate-900">
                                        {{ $package->name }}
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        {{ $package->remarks ?: 'Everything you need to manage your business.' }}
                                    </p>

                                    {{-- Price --}}
                                    <div class="mt-7 pb-7 border-b border-slate-100">
                                        <div class="flex items-end gap-1">
                                            <span class="text-4xl font-extrabold text-slate-900">
                                                ৳{{ number_format($package->price, 0) }}
                                            </span>
                                            <span class="text-sm text-slate-500 mb-1">
                                                / package
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Features --}}
                                    <div class="mt-7">
                                        <h4 class="text-sm font-bold text-slate-900 mb-5">
                                            What's included:
                                        </h4>
                                        <ul class="space-y-4 text-sm text-slate-600">
                                            @php
                                                $features = [
                                                    'Companies' => $package->company_limit,
                                                    'Branches' => $package->branch_limit,
                                                    'Users' => $package->user_limit,
                                                    'Suppliers' => $package->supplier_limit,
                                                    'Customers' => $package->customer_limit,
                                                    'Payment Types' => $package->payment_type_limit,
                                                    'Accounts' => $package->account_limit,
                                                    'Products' => $package->product_limit,
                                                    'Sales Orders' => $package->sales_order_limit,
                                                    'Storage' => $package->storage_limit . ' MB',
                                                ];
                                            @endphp
                                            @foreach ($features as $label => $value)
                                                <li class="flex items-center justify-between gap-3">
                                                    <span class="flex items-center gap-3">
                                                        <span
                                                            class="flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-600">
                                                            <i class="fas fa-check text-[10px]"></i>
                                                        </span>
                                                        {{ $label }}
                                                    </span>
                                                    <span class="font-semibold text-slate-800 text-right">
                                                        @if ($value === 'Unlimited' || $value === '-1')
                                                            Unlimited
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                {{-- Button --}}
                                <div class="p-8 pt-0">
                                    <form action="{{ route('payment.sslcommerz.initiate', $package->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center cursor-pointer justify-center gap-2 w-full py-3.5 px-6 rounded-xl font-bold text-sm transition duration-300
                                            {{ $popular
                                                ? 'bg-cyan-600 text-white hover:bg-cyan-700 shadow-lg shadow-cyan-200'
                                                : 'bg-slate-900 text-white hover:bg-cyan-600' }}">
                                            Get Started
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-slate-500 text-lg">
                                    No packages available at the moment.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Bottom Note --}}
                    <div class="text-center mt-14">
                        <p class="text-slate-500 text-sm">
                            Need a customized solution for your business?
                            <a href="#contact" class="font-semibold text-cyan-600 hover:text-cyan-700">
                                Contact our team
                            </a>
                        </p>
                    </div>

                </div>
            </section>

            <!-- ================= CONTACT SECTION ================= -->
            <section id="contact" class="pb-16 pt-8 bg-gradient-to-br from-cyan-50 via-white to-cyan-50">
                <div class="max-w-7xl mx-auto px-5 lg:px-8">
                    <!-- Heading -->
                    <div class="max-w-3xl mx-auto text-center">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-600 font-semibold">
                            <i class="fa-solid fa-headset"></i>
                            {{ __('common.contact_us') }}
                        </span>
                    </div>
                    <!-- Contact Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <!-- Address -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-8 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div
                                class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-red-500 via-red-300 to-red-100 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500">
                            </div>
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-gradient-to-br from-red-100 to-red-100 shadow-md transition duration-500 group-hover:scale-110">
                                <i class="fa-solid fa-location-dot text-2xl text-red-600"></i>
                            </div>
                            <h3 class="mt-6 text-xl font-bold text-slate-900">
                                {{ __('common.our_address') }}
                            </h3>
                            <p class="mt-3 text-slate-600 leading-7">
                                3<sup>rd</sup> floor,
                                House-375, Road-28<br>
                                DOHS Mohakhali<br>
                                Dhaka-1212, Bangladesh
                            </p>
                        </div>
                        <!-- Phone -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-8 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div
                                class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500">
                            </div>
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-gradient-to-br from-emerald-100 to-green-100 shadow-md transition duration-500 group-hover:scale-110">
                                <i class="fa-solid fa-phone text-2xl text-emerald-600"></i>
                            </div>
                            <h3 class="mt-6 text-xl font-bold text-slate-900">
                                {{ __('common.phone_number') }}
                            </h3>
                            <p class="mt-3 text-slate-600 text-lg font-medium">
                                {{ __('common.phone_one') }}
                                <br>
                                {{ __('common.phone_two') }}
                            </p>
                        </div>
                        <!-- Email -->
                        <div
                            class="group relative overflow-hidden rounded-sm border border-slate-200 bg-white p-8 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                            <div
                                class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-cyan-500 via-cyan-300 to-cyan-100 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500">
                            </div>
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-sm bg-gradient-to-br from-cyan-100 to-cyan-100 shadow-md transition duration-500 group-hover:scale-110">
                                <i class="fa-solid fa-envelope text-2xl text-cyan-600"></i>
                            </div>
                            <h3 class="mt-6 text-xl font-bold text-slate-900">
                                {{ __('common.email_address') }}
                            </h3>
                            <p class="mt-3 text-slate-600 break-all">
                                sales@bims.bd
                            </p>
                        </div>
                    </div>
                    <!-- Map + Form -->
                    <div class="flex gap-8 mt-5" id="form">
                        <!-- Google Map -->
                        <div class="overflow-hidden rounded-sm border border-slate-200 shadow-lg w-1/2">
                            <iframe
                                src="https://www.google.com/maps?q=House+375+Road+28+DOHS+Mohakhali+Dhaka&output=embed"
                                class="w-full h-full border-0" loading="lazy">
                            </iframe>
                        </div>

                        <!-- Form -->
                        <div class="bg-white rounded-sm border border-slate-200 shadow-lg p-4 lg:p-5 w-1/2">
                            @if (session('success'))
                                <div class="mb-4 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700"
                                    role="alert">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                                                clip-rule="evenodd" />
                                        </svg>

                                        <span>{{ session('success') }}</span>
                                    </div>

                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="ml-4 text-green-600 hover:text-green-900" aria-label="Close">
                                        ✕
                                    </button>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                                @csrf
                                <input type="text" name="name" value="{{ old('name') }}"
                                    placeholder="Your Name" required
                                    class="w-full rounded-sm border border-slate-300 px-5 py-3.5 outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    placeholder="Phone Number" required
                                    class="w-full rounded-sm border border-slate-300 px-5 py-3.5 outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="Email Address" required
                                    class="w-full rounded-sm border border-slate-300 px-5 py-3.5 outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                                <textarea rows="4" name="message" placeholder="Tell us about your business..."
                                    class="w-full rounded-sm border border-slate-300 px-5 py-3 outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                                <button type="submit"
                                    class="w-100 rounded-sm bg-gradient-to-r from-cyan-600 to-cyan-500 py-3.5 text-base font-semibold text-white hover:shadow-xl hover:scale-[1.02] transition-all cursor-pointer">
                                    {{ __('common.request_free_demo') }}
                                </button>
                            </form>

                            <p class="mt-5 flex items-center justify-center gap-2 text-center text-sm text-slate-500">
                                <i class="fa-solid fa-lock text-slate-500"></i>
                                <span>
                                    Your information is secure. We'll never share your personal information.
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            @include('FrontEnd.Home.footer')

            @include('FrontEnd.Home.Footer.implementation')
            @include('FrontEnd.Home.Footer.data_migration')
            @include('FrontEnd.Home.Footer.user_training')
            @include('FrontEnd.Home.Footer.tecnical_support')
            @include('FrontEnd.Home.Footer.customization')
            @include('FrontEnd.Home.Footer.maintenance')
            @include('FrontEnd.Home.Footer.about_us')
            @include('FrontEnd.Home.Footer.privacy')
            @include('FrontEnd.Home.Footer.terms')

            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

            @include('FrontEnd.Home.script')
    </body>

</html>
