<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>COMITS</title>
        <link rel="stylesheet" href="assets/css/style.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" />
        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
        <style type="text/tailwindcss">
            @theme {
                --font-display: "Inter", sans-serif;

                --color-primary: #2563EB;
                --color-secondary: #06B6D4;
                --color-dark: #0F172A;
                --color-light: #F8FAFC;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                font-family: var(--font-display);
            }

            .sojib {
                @apply max-w-7xl mx-auto px-6 lg:px-8;
            }

            .aporna {
                @apply bg-transparent text-lg font-medium text-slate-600 border-none focus:outline-none cursor-pointer;
            }

            .dashboard-wrapper {
                @apply relative w-fit mx-auto;
            }

            .dashboard-card {
                @apply rounded-[32px] bg-white border border-slate-200 shadow-2xl overflow-hidden;
            }

            .dashboard-growth {
                @apply absolute -left-12 top-20 bg-white border border-slate-200 rounded-3xl shadow-xl px-6 py-5 z-20;
            }

            .dashboard-customers {
                @apply absolute -right-10 bottom-16 bg-white border border-slate-200 rounded-3xl shadow-xl px-6 py-5 z-20;
            }

            .dashboard-glow {
                position: absolute;
                inset: 0;
                z-index: -1;
                filter: blur(120px);
                background: linear-gradient(90deg, rgba(103, 232, 249, .35), rgba(96, 165, 250, .25), rgba(134, 239, 172, .35));
            }

            .contact-section {
                scroll-margin-top: 120px;
            }

            .card1 {
                @apply rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all duration-500 flex flex-col items-center text-center hover:-translate-y-2 hover:shadow-xl;
            }

            .card1:hover {
                background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(6, 182, 212, 0.12));
                border-color: rgba(37, 99, 235, 0.2);
            }

            .card2 {
                @apply w-14 h-14 rounded-xl flex items-center justify-center transition-all duration-500;
            }

            .card1:hover .card2 {
                transform: scale(1.1);
            }

            .billing-btn {
                color: #708097;
                /* text-slate-600 */
                background-color: transparent;
            }

            .billing-btn.active {
                color: #ffffff;
                background-color: #3962c0;
                /* bg-slate-900 */
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .pricing-card {
                @apply relative flex flex-col h-full bg-white rounded-xl border border-slate-300 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg;
            }

            .pricingSlider {
                padding: 10px 0 50px;
            }

            .pricingSlider .swiper-slide {
                height: auto;
            }

            .pricing-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 45px rgba(37, 99, 235, .15);
            }

            .pricing-header {
                @apply bg-slate-50 p-6 text-center border-b border-slate-200 transition-colors duration-300 relative;
            }

            .price-section {
                @apply flex flex-col items-center;
            }

            .old-price-wrapper {
                @apply h-6 flex items-center justify-center;
            }

            .old-price {
                @apply text-sm text-slate-400 line-through font-medium;
            }

            .price-wrapper {
                @apply flex justify-center items-baseline gap-1 mt-1 text-slate-900;
            }

            @media (min-width:1280px) {

                .pricingSlider .swiper-wrapper {

                    display: grid;
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                    gap: 2rem;
                }
            }

            .header-free {
                @apply border-t-4 border-t-slate-400;
            }

            .header-basic {
                @apply border-t-4 border-t-blue-500;
            }

            .header-standard {
                @apply border-t-4 border-t-[#3962c0] bg-blue-50/50;
            }

            .header-enterprise {
                @apply border-t-4 border-t-indigo-600;
            }

            .hero-section {
                @apply relative overflow-hidden bg-gradient-to-br from-blue-50 pt-32 via-white to-cyan-50;
            }

            .hero-container {
                @apply relative max-w-7xl mx-auto px-6 lg:px-8 pt-10 pb-20 lg:pt-14 lg:pb-24 grid lg:grid-cols-2 gap-16 items-center;
            }

            .hero-blur-left {
                @apply absolute -top-40 -left-32 w-96 h-96 rounded-full bg-blue-300 opacity-20 blur-3xl;
            }

            .hero-blur-right {
                @apply absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full bg-cyan-300 opacity-20 blur-3xl;
            }

            .dashboard-card {
                @apply overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl;
            }

            .dashboard-header {
                @apply flex items-center justify-between border-b px-6 py-4;
            }

            .dashboard-body {
                @apply space-y-5 p-6;
            }

            .dashboard-item {
                @apply flex justify-between rounded-xl bg-slate-50 p-4;
            }

            .floating-card {
                @apply absolute bg-white rounded-3xl border border-slate-200 shadow-xl px-6 py-5 z-20;
                animation: float 4s ease-in-out infinite;
            }

            .floating-left {
                top: -60px;
                left: -120px;
            }

            .floating-right {
                bottom: -60px;
                right: -60px;
                animation-delay: 2s;
            }

            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-12px);
                }

                100% {
                    transform: translateY(0px);
                }
            }

            .ribbon {
                @apply absolute top-3 -left-8 bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider py-1 w-28 text-center -rotate-45 shadow-sm z-10;
            }

            /* Body */
            .card-body {
                @apply p-6 flex flex-col flex-1 justify-between gap-6 bg-white;
            }

            .feature {
                @apply text-center text-sm py-1;
            }

            .feature.active {
                @apply text-slate-700 font-medium;
            }

            .feature.active i {
                @apply text-[#3962c0] text-base;
            }

            .feature.inactive {
                @apply text-slate-300 line-through decoration-slate-300;
            }

            .feature.inactive i {
                @apply text-slate-200 text-base;
            }

            /* Action Button */
            .plan-btn {
                @apply w-full py-3 px-4 rounded-lg font-bold text-sm tracking-wide text-slate-800 bg-slate-100/90 border border-slate-200/80 transition-all duration-300 ease-in-out cursor-pointer;
            }

            .plan-btn:hover {
                @apply bg-[#3962c0] text-white border-[#3962c0] shadow-md -translate-y-0.5;
            }

            .plan-btn:active {
                @apply translate-y-0 shadow-none;
            }

            /* Highlight standard plan button */
            .pricing-card:has(.ribbon) .plan-btn {
                @apply bg-[#3962c0] text-white hover:bg-blue-700;
            }

            .old-price-wrapper {
                @apply h-5 flex items-center justify-center mb-1;
            }

            .old-price {
                @apply text-sm text-slate-400 line-through font-medium;
            }

            .price-wrapper {
                @apply flex items-baseline justify-center gap-1.5 whitespace-nowrap w-full overflow-hidden px-1;
            }

            .currency {
                @apply text-2xl font-extrabold mr-0.5;
            }

            .new-price {
                @apply text-3xl xl:text-4xl font-extrabold text-slate-900 tracking-tight leading-none;
            }

            .duration {
                @apply text-xs xl:text-sm font-semibold text-slate-500 whitespace-nowrap flex-shrink-0;
            }

            .partner-section {
                @apply relative py-20 overflow-hidden bg-gradient-to-br from-blue-100 via-white to-cyan-100;
            }

            .card-body {
                @apply p-6 flex flex-col flex-1 justify-between gap-6 bg-white;
            }

            .partner-container {
                @apply relative max-w-7xl mx-auto px-6 lg:px-8;
            }

            .logo-card {
                @apply w-24 h-24 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm cursor-pointer transition-all duration-500;
            }

            .logo-card:hover {
                @apply border-blue-500 shadow-2xl;
                transform: translateY(-6px) scale(0.96);
            }

            .logo-image {
                @apply w-16 h-16 object-contain transition-all duration-500;
            }

            .logo-card:hover .logo-image {
                transform: scale(0.92);
            }

            .logo-slider {
                @apply py-6;
            }

            .logo-slide {
                @apply flex justify-center items-center;
            }

            .payment-wrapper {
                @apply mt-16 py-10 border-y border-blue-200;
            }

            .payment-banner {
                @apply w-full h-auto;
            }

            #backToTop {
                @apply fixed bottom-6 right-6 z-50 p-3 rounded-full bg-[#3962c0] text-white shadow-lg transition-all duration-300 opacity-0 pointer-events-none translate-y-4;
            }

            /* Shown state triggered past 100vh */
            #backToTop.show {
                @apply opacity-100 pointer-events-auto translate-y-0;
            }

            .footer {
                background: #fafbfc;
                border-top: 1px solid #e5e7eb;
                padding: 80px 0 0;
            }
        </style>
    </head>

    <body class="bg-light text-dark">
        <header id="navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent transition-all duration-300">
            <!-- TOP BAR (Will hide on scroll) -->
            <div id="topBar"
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300 overflow-hidden">
                <div class="flex justify-between sm:justify-end items-center h-10 gap-4 sm:gap-8">
                    <!-- Phone Number -->
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <i class="fa-solid fa-phone text-blue-500 text-xs sm:text-sm"></i>
                        <span class="text-xs sm:text-sm md:text-base font-medium text-slate-600">
                            +880 152121212212
                        </span>
                    </div>
                    <!-- Language Selector -->
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <i class="fa-solid fa-globe text-emerald-500 text-xs sm:text-sm"></i>
                        <select id="languageSelect"
                            class="bg-transparent border-0 outline-none text-xs sm:text-sm md:text-base text-slate-600 cursor-pointer font-medium">
                            <option value="en">English</option>
                            <option value="bn">বাংলা</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- MAIN NAVBAR (Stays visible) -->
            <div id="mainNav" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300">
                <div class="flex items-center justify-between py-2 sm:py-3">
                    <!-- Logo -->
                    <a href="#" class="flex-shrink-0">
                        <img src="assets/images/logo/comits.png" class="h-9 sm:h-11 md:h-12 w-auto object-contain"
                            alt="COMITS">
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden lg:flex items-center gap-8 xl:gap-10">
                        <a href="#" class="font-medium text-slate-700 hover:text-blue-600 transition">Home</a>
                        <a href="#features"
                            class="font-medium text-slate-700 hover:text-blue-600 transition">Features</a>
                        <a href="#pricing"
                            class="font-medium text-slate-700 hover:text-blue-600 transition">Packages</a>
                        <a href="#contact" class="font-medium text-slate-700 hover:text-blue-600 transition">Contact</a>
                    </nav>

                    <!-- Desktop Action Buttons -->
                    <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                        <button
                            class="px-5 py-2 border border-blue-600 rounded-xl text-blue-600 font-semibold hover:bg-blue-600 hover:text-white transition-all duration-300">
                            Login
                        </button>
                        <button
                            class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all duration-300 shadow-sm hover:shadow">
                            Free Trial
                        </button>
                    </div>

                    <!-- Mobile Hamburger Button -->
                    <button id="menuBtn" class="lg:hidden text-2xl sm:text-3xl text-slate-800 p-1 focus:outline-none">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- MOBILE MENU DROPDOWN -->
            <div id="mobileMenu"
                class="hidden lg:hidden bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-xl">
                <div class="flex flex-col p-5 gap-3.5 text-slate-700 font-medium">
                    <a href="#" class="py-1 hover:text-blue-600 transition">Home</a>
                    <a href="#features" class="py-1 hover:text-blue-600 transition">Features</a>
                    <a href="#pricing" class="py-1 hover:text-blue-600 transition">Packages</a>
                    <a href="#contact" class="py-1 hover:text-blue-600 transition">Contact</a>
                    <hr class="border-slate-100 my-1">
                    <div class="flex flex-col gap-2 pt-1">
                        <button
                            class="w-full border border-blue-600 text-blue-600 font-semibold rounded-lg py-2.5 hover:bg-blue-50 transition">
                            Login
                        </button>
                        <button
                            class="w-full bg-emerald-600 text-white font-semibold rounded-lg py-2.5 hover:bg-emerald-700 transition">
                            Free Trial
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <main class="">
            <section class="hero-section">
                <div class="hero-blur-left"></div>
                <div class="hero-blur-right">
                </div>
                <div class="hero-container">
                    <div>
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                            Smart Company Management System
                        </span>
                        <h1 class="mt-6 text-4xl font-extrabold leading-tight text-slate-900 lg:text-4xl">
                            Manage Your
                            <span class="text-blue-600"></span>
                            Smarter with
                            COMITS
                        </h1>
                        <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                            COMITS helps you manage employees, payroll, inventory,
                            sales, accounting, attendance, and reports from one
                            modern dashboard.
                        </p>
                        <div class="mt-10 flex flex-wrap gap-4">
                            <button
                                class="rounded-xl border border-slate-300 bg-blue-600 px-6 py-2 text-lg text-white transition hover:shadow-lg">
                                Get Started
                            </button>
                            <button
                                class="rounded-xl border border-slate-300 bg-green-600 px-6 py-2 text-lg text-white transition hover:shadow-lg">
                                Free Trial
                            </button>
                            <button
                                class="rounded-xl border border-slate-300 bg-white px-6 py-2 text-lg transition hover:shadow-lg">
                                Demo Request
                            </button>
                        </div>
                        <div class="mt-14 flex flex-wrap gap-10">
                            <div>
                                <h3 class="text-3xl font-bold text-blue-600">500+</h3>
                                <p class="text-slate-500">Companies</p>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold text-blue-600">10K+</h3>
                                <p class="text-slate-500">Users</p>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold text-blue-600">99.9%</h3>
                                <p class="text-slate-500">Uptime</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="dashboard-card">
                            <div class="dashboard-header">
                                <h3 class="font-bold">
                                    Dashboard Overview
                                </h3>
                                <span class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-600">
                                    Live
                                </span>
                            </div>
                            <div class="dashboard-body">
                                <div class="dashboard-item">
                                    <span>Total Revenue</span>
                                    <strong>$52,450</strong>
                                </div>
                                <div class="dashboard-item">
                                    <span>Employees</span>
                                    <strong>186</strong>
                                </div>
                                <div class="dashboard-item">
                                    <span>Inventory</span>
                                    <strong>1,245 Items</strong>
                                </div>
                                <div class="dashboard-item">
                                    <span>Today's Sales</span>
                                    <strong>$5,240</strong>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card floating-left">
                            <h4 class="text-sm text-slate-500">
                                Monthly Growth
                            </h4>
                            <p class="text-2xl font-bold text-green-600">
                                +24%
                            </p>
                        </div>
                        <div class="floating-card floating-right">
                            <h4 class="text-sm text-slate-500">
                                New Customers
                            </h4>
                            <p class="text-2xl font-bold text-blue-600">
                                324
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="py-8 bg-white">
                <div class="sojib">
                    <div class="max-w-3xl mx-auto text-center">
                        <span
                            class="inline-block px-4 py-2 rounded-full bg-blue-100 text-blue-600 text-sm font-semibold">
                            Features
                        </span>
                        <h2 class="mt-5 text-3xl font-extrabold text-slate-900">
                            Everything Your Business Needs
                        </h2>
                        <p class="mt-6 text-lg text-slate-600 leading-8">
                            COMITS brings all your essential business operations into
                            one secure and easy-to-use platform, helping your team
                            save time, improve productivity, and make better decisions.
                        </p>
                    </div>

                    <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-4 items-stretch">

                        <!-- Card 1 -->
                        <div
                            class="card1 group relative overflow-hidden bg-white border border-slate-200 rounded-xl p-6 flex flex-col items-center text-center before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                            <div class="card2 group bg-yellow-100 p-3 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor"
                                    class="w-9 h-9 text-yellow-500">
                                    <path
                                        d="M341.5 45.1C337.4 37.1 329.1 32 320.1 32C311.1 32 302.8 37.1 298.7 45.1L225.1 189.3L65.2 214.7C56.3 216.1 48.9 222.4 46.1 231C43.3 239.6 45.6 249 51.9 255.4L166.3 369.9L141.1 529.8C139.7 538.7 143.4 547.7 150.7 553C158 558.3 167.6 559.1 175.7 555L320.1 481.6L464.4 555C472.4 559.1 482.1 558.3 489.4 553C496.7 547.7 500.4 538.8 499 529.8L473.7 369.9L588.1 255.4C594.5 249 596.7 239.6 593.9 231C591.1 222.4 583.8 216.1 574.8 214.7L415 189.3L341.5 45.1z" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">
                                Review Rating
                            </h3>
                            <p class="mt-3 text-slate-600">
                                Manage employees, attendance, leave requests, departments, and employee records.
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="card1 group relative overflow-hidden bg-white border border-slate-200 rounded-xl p-6 flex flex-col items-center text-center before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                            <div
                                class="card2 group bg-green-100 p-3 rounded-lg flex items-center justify-center text-xl text-green-600">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">
                                Active Users
                            </h3>
                            <p class="mt-3 text-slate-600">
                                Track stock, manage warehouses, products, suppliers, and purchase orders.
                            </p>
                        </div>

                        <!-- Card 3 -->
                        <div
                            class="card1 group relative overflow-hidden bg-white border border-slate-200 rounded-xl p-6 flex flex-col items-center text-center before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                            <div
                                class="card2 group bg-yellow-100 p-3 rounded-lg flex items-center justify-center text-xl text-yellow-600">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">
                                Multiple Branch
                            </h3>
                            <p class="mt-3 text-slate-600 leading-7">
                                Create invoices, manage customers, monitor sales, and generate receipts.
                            </p>
                        </div>

                        <!-- Card 4 -->
                        <div
                            class="card1 group relative overflow-hidden bg-white border border-slate-200 rounded-xl p-6 flex flex-col items-center text-center before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                            <div
                                class="card2 group bg-purple-100 p-3 rounded-lg flex items-center justify-center text-xl text-purple-600">
                                <i class="fa-brands fa-angular"></i>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">
                                Web & Mobile Application
                            </h3>
                            <p class="mt-3 text-slate-600">
                                View real-time charts, reports, KPIs, and business performance.
                            </p>
                        </div>

                    </div>

                </div>
            </section>
            <section class="partner-section">
                <div class="partner-container">
                    <div class="text-center mb-12">
                        <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-600 font-semibold">
                            Partners
                        </span>
                        <h2 class="mt-5 text-3xl font-bold text-slate-900">
                            Our Partners
                        </h2>
                        <p class="mt-4 text-slate-600 text-lg max-w-2xl mx-auto">
                            Trusted by leading companies across different industries who rely on COMITS
                            to manage their business efficiently.
                        </p>
                    </div>
                    <div class="swiper partnerSlider logo-slider">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Agrey Handicraft logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>

                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Computer Point logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>

                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Enlive logo.jpg" class="logo-image" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/HM electronics logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Khati Organic logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Khatir Choya logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Ping Pong logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Wisdom Electronics BD logo.jpg" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                            <div class="swiper-slide logo-slide">
                                <div class="logo-card">
                                    <img src="assets/images/logo/Yes Electronics logo.png" class="logo-image"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="pricing" class="py-5 bg-slate-50">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">

                    <!-- Section Header -->
                    <div class="text-center max-w-2xl mx-auto">
                        <span
                            class="inline-flex px-4 py-1.5 rounded-full bg-blue-100 text-blue-600 text-sm font-semibold tracking-wide uppercase">
                            Pricing
                        </span>
                        <h2 class="mt-3 text-3xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Packages & Pricing
                        </h2>
                        <p class="mt-4 text-slate-600 text-base sm:text-lg">
                            Subscribe to the suitable package for your business.
                        </p>
                    </div>

                    <!-- Duration Switcher -->
                    <div class="flex justify-center mt-8">
                        <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm gap-1">
                            <button
                                class="billing-btn active px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200"
                                data-duration="1">
                                1 Month
                            </button>
                            <button
                                class="billing-btn px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-200"
                                data-duration="3">
                                3 Months
                            </button>
                            <button
                                class="billing-btn px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-200"
                                data-duration="6">
                                6 Months
                            </button>
                        </div>
                    </div>

                    <!-- Cards Slider -->
                    <div class="swiper pricingSlider mt-10">
                        <div id="pricingCards" class="swiper-wrapper py-4">
                            <!-- Javascript dynamic cards render here -->
                        </div>
                        <div class="swiper-pagination !relative !bottom-0 mt-8"></div>
                    </div>

                </div>
            </section>

            <section id="contact" class="contact-section py-5 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-16">

                    <section id="contact"
                        class="contact-section py-5 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
                        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-5">

                            <!-- DEMO FORM SECTION -->
                            <div>
                                <div class="text-center">
                                    <span
                                        class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-600 font-semibold">
                                        Contact Us
                                    </span>

                                    <h2 class="mt-5 text-3xl font-bold">
                                        Request a Free Demo
                                    </h2>

                                    <p class="mt-4 mb-4 text-slate-600 text-lg">
                                        Fill out the form below and our representative will contact you shortly.
                                    </p>
                                </div>

                                <!-- TOP 3 CONTACT INFO CARDS -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                    <!-- Address Card -->
                                    <div
                                        class="relative overflow-hidden bg-slate-100/90 rounded-md p-6 text-center border border-slate-200/80 shadow-sm flex flex-col items-center justify-center space-y-3 before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                                        <div class="text-[#3962c0] text-xl">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">Our Address</h3>
                                        <p class="text-sm text-slate-600 leading-relaxed max-w-xs">
                                            Comits Computers, 3rd Floor, House: 375, Road: 28, DOHS Mohakhali,
                                            Dhaka-1212,
                                            Bangladesh
                                        </p>
                                    </div>

                                    <!-- Phone Card -->
                                    <div
                                        class="relative overflow-hidden bg-slate-100/90 rounded-md p-6 text-center border border-slate-200/80 shadow-sm flex flex-col items-center justify-center space-y-3 before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                                        <div class="text-[#3962c0] text-xl">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">Phone Number</h3>
                                        <p class="text-sm text-slate-600">
                                            +8801711257224
                                        </p>
                                    </div>

                                    <!-- Email Card -->
                                    <div
                                        class="relative overflow-hidden bg-slate-100/90 rounded-md p-6 text-center border border-slate-200/80 shadow-sm flex flex-col items-center justify-center space-y-3 before:absolute before:left-0 before:top-0 before:bottom-0 before:w-1 before:bg-[#3962c0]">
                                        <div class="text-[#3962c0] text-xl">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">Email Address</h3>
                                        <p class="text-sm text-slate-600">
                                            info@comitsbd.com
                                        </p>
                                    </div>

                                </div>

                                <!-- Two-column layout -->
                                <div class="grid lg:grid-cols-2 gap-16 items-center mt-12">

                                    <!-- Left Image -->
                                    <div>
                                        <img src="assets/images/logo/contact-img.png" class="w-full" alt="Contact">
                                    </div>

                                    <!-- Right Form -->
                                    <form class="space-y-6" onsubmit="event.preventDefault();">

                                        <input type="text" placeholder="Your Name"
                                            class="w-full rounded-sm border border-slate-300 px-5 py-3 focus:border-blue-500 focus:outline-none bg-white placeholder:text-slate-400 text-slate-800"
                                            required>

                                        <input type="tel" placeholder="Phone Number"
                                            class="w-full rounded-sm border border-slate-300 px-5 py-3 focus:border-blue-500 focus:outline-none bg-white placeholder:text-slate-400 text-slate-800"
                                            required>

                                        <input type="email" placeholder="Email Address"
                                            class="w-full rounded-sm border border-slate-300 px-5 py-3 focus:border-blue-500 focus:outline-none bg-white placeholder:text-slate-400 text-slate-800"
                                            required>

                                        <select
                                            class="w-full rounded-sm border border-slate-300 px-5 py-3 focus:border-blue-500 focus:outline-none bg-white text-slate-600 cursor-pointer"
                                            required>
                                            <option value="" disabled selected>Select Business Type</option>
                                            <option value="retail">Retail / Super Shop</option>
                                            <option value="wholesale">Wholesale & Distribution</option>
                                            <option value="pharmacy">Pharmacy & Healthcare</option>
                                            <option value="restaurant">Restaurant & POS</option>
                                            <option value="other">Other Business</option>
                                        </select>

                                        <button type="submit"
                                            class="px-6 rounded-lg bg-[#3962c0] py-3 text-lg font-semibold text-white hover:bg-[#2d4ea0] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 shadow-sm hover:shadow-md cursor-pointer">
                                            <span>Submit</span>
                                            <i class="fa-solid fa-arrow-right text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </section>

                </div>
            </section>
            <!-- FOOTER START -->
            <footer
                class="bg-gradient-to-b from-sky-50 to-white text-slate-700 pt-16 border-t border-slate-200/80 font-sans">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-8">

                        <!-- NEWSLETTER -->
                        <div class="lg:col-span-4 space-y-4">
                            <div class="inline-block">
                                <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">Newsletter</h3>
                                <div class="h-1 w-12 bg-blue-600 rounded-full mt-1"></div>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed text-justify">
                                Innovative IT solutions, web & mobile apps, and digital growth — ComitsBD empowers your
                                business for the future.
                            </p>
                            <form onsubmit="event.preventDefault();" class="mt-4">
                                <div
                                    class="flex items-center rounded-lg border border-slate-300 overflow-hidden bg-white focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600 shadow-sm transition">
                                    <input type="email" placeholder="Email Address"
                                        class="w-full py-3 px-4 text-sm text-slate-800 focus:outline-none placeholder:text-slate-400"
                                        required />
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3.5 transition-colors flex items-center justify-center"
                                        aria-label="Subscribe">
                                        <i class="fa-solid fa-paper-plane text-sm"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- SERVICES -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="inline-block">
                                <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">Services</h3>
                                <div class="h-1 w-10 bg-blue-600 rounded-full mt-1"></div>
                            </div>
                            <ul class="space-y-2 text-sm">
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>IT Support</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>CCTV & Security Services</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>Hardware Solutions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>Software</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- COMPANY -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="inline-block">
                                <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">Company</h3>
                                <div class="h-1 w-10 bg-blue-600 rounded-full mt-1"></div>
                            </div>
                            <ul class="space-y-2 text-sm">
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>About Us</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>Portfolio</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>FAQ</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>Privacy policy</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="group flex items-center text-slate-600 hover:text-blue-600 transition-colors">
                                        <i
                                            class="fa-solid fa-caret-right text-blue-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                                        <span>Terms & Conditions</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- CONTACT DETAILS -->
                        <div class="lg:col-span-4 space-y-4">
                            <div class="inline-block">
                                <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">Contact Details
                                </h3>
                                <div class="h-1 w-12 bg-blue-600 rounded-full mt-1"></div>
                            </div>

                            <div class="space-y-4 text-sm text-slate-600">
                                <!-- Address (Centered) -->
                                <div class="flex items-center gap-3.5 group">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded-xl bg-red-50 border border-red-100/80 flex items-center justify-center text-red-600 shadow-xs group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                                        <i class="fa-solid fa-location-dot text-base"></i>
                                    </div>
                                    <span class="leading-relaxed text-slate-700">
                                        Comits Computers, 3rd Floor, House: 375, Road: 28, DOHS Mohakhali, Dhaka-1212,
                                        Bangladesh
                                    </span>
                                </div>

                                <!-- Phone (Centered) -->
                                <div class="flex items-center gap-3.5 group">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded-xl bg-green-50 border border-green-100/80 flex items-center justify-center text-green-600 shadow-xs group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                                        <i class="fa-solid fa-phone text-sm"></i>
                                    </div>
                                    <div class="space-y-1 leading-snug">
                                        <p><span class="font-semibold text-slate-800">Sales:</span> <a
                                                href="tel:+8801711257224"
                                                class="hover:text-blue-600 transition">+8801711257224</a></p>
                                        <p><span class="font-semibold text-slate-800">Support:</span> <a
                                                href="tel:+8801331995522"
                                                class="hover:text-blue-600 transition">+8801331995522</a></p>
                                    </div>
                                </div>

                                <!-- Email (Centered) -->
                                <div class="flex items-center gap-3.5 group">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded-xl bg-blue-50 border border-blue-100/80 flex items-center justify-center text-blue-600 shadow-xs group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                        <i class="fa-solid fa-envelope text-sm"></i>
                                    </div>
                                    <div class="space-y-1 leading-snug">
                                        <p><a href="mailto:info@comitsbd.com"
                                                class="hover:text-blue-600 transition">info@comitsbd.com</a></p>
                                        <p><a href="mailto:support@comitsbd.com"
                                                class="hover:text-blue-600 transition">support@comitsbd.com</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COPYRIGHT BAR -->
                <div class="bg-slate-900 text-slate-300 py-4 border-t border-slate-800">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                        <p>
                            Copyright © 2026 <a href="#"
                                class="text-blue-400 font-medium hover:underline">ComitsBD</a>. All
                            rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
            <!-- FOOTER END -->
            <div class="fixed left-0 top-1/2 -translate-y-1/2 z-50 flex flex-col space-y-1">
                <!-- Facebook Icon -->
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                    class="bg-[#1877F2] text-white p-3 rounded-r-md flex items-center justify-center shadow-md transition-transform duration-300 -translate-x-2 hover:translate-x-0">
                    <i class="fa-brands fa-facebook-f text-lg"></i>
                </a>

                <!-- WhatsApp Icon -->
                <a href="https://wa.me/8801711257224" target="_blank" rel="noopener noreferrer"
                    class="bg-[#25D366] text-white p-3 rounded-r-md flex items-center justify-center shadow-md transition-transform duration-300 -translate-x-2 hover:translate-x-0">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                </a>

                <!-- Email Icon -->
                <a href="mailto:info@comitsbd.com"
                    class="bg-slate-600 text-white p-3 rounded-r-md flex items-center justify-center shadow-md transition-transform duration-300 -translate-x-2 hover:translate-x-0">
                    <i class="fa-solid fa-envelope text-lg"></i>
                </a>
            </div>
            <button id="backToTop" class="back-to-top">
                <i class="fa-solid fa-arrow-up"></i>
            </button>
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script src="assets/js/app.js"></script>
    </body>

    <script>
        const pricingData = [{
                name: "FREE 14 DAYS TRIAL",
                fixed: true,
                header: "header-free",
                button: "Take Free Trial",
                prices: {
                    1: {
                        old: "",
                        new: "0",
                        duration: "14 Days Trial"
                    },
                },
                features: [{
                        text: "Sale, Purchase Management",
                        available: true
                    },
                    {
                        text: "Accounts Management",
                        available: true
                    },
                    {
                        text: "Inventory Management",
                        available: true
                    },
                    {
                        text: "Order Management",
                        available: true
                    },
                    {
                        text: "E-Commerce Website Integration",
                        available: false
                    },
                    {
                        text: "30 Invoices (1 Month)",
                        available: true
                    },
                    {
                        text: "No. Of User - 1",
                        available: true
                    },
                    {
                        text: "No. Of Branch - 1",
                        available: true
                    },
                    {
                        text: "Online Training & Support",
                        available: true
                    }
                ]
            },
            {
                name: "BASIC",
                header: "header-basic",
                button: "Subscribe Now",
                prices: {
                    1: {
                        old: "7200",
                        new: "6200",
                        duration: "Year"
                    },
                    3: {
                        old: "18000",
                        new: "15500",
                        duration: "3 Months"
                    },
                    6: {
                        old: "36000",
                        new: "30000",
                        duration: "6 Months"
                    }
                },
                features: [{
                        text: "Sale, Purchase Management",
                        available: true
                    },
                    {
                        text: "Accounts Management",
                        available: true
                    },
                    {
                        text: "Inventory Management",
                        available: true
                    },
                    {
                        text: "Order Management",
                        available: true
                    },
                    {
                        text: "E-Commerce Website Integration",
                        available: false
                    },
                    {
                        text: "200 Invoices (1 Month)",
                        available: true
                    },
                    {
                        text: "No. Of User - 1",
                        available: true
                    },
                    {
                        text: "Branch",
                        available: false
                    },
                    {
                        text: "Online Training & Support",
                        available: true
                    }
                ]
            },
            {
                name: "STANDARD",
                header: "header-standard",
                popular: true,
                button: "Subscribe Now",
                prices: {
                    1: {
                        old: "13200",
                        new: "11220",
                        duration: "Year"
                    },
                    3: {
                        old: "33000",
                        new: "28000",
                        duration: "3 Months"
                    },
                    6: {
                        old: "66000",
                        new: "55000",
                        duration: "6 Months"
                    }
                },
                features: [{
                        text: "Sale, Purchase Management",
                        available: true
                    },
                    {
                        text: "Accounts Management",
                        available: true
                    },
                    {
                        text: "Inventory Management",
                        available: true
                    },
                    {
                        text: "Order Management",
                        available: true
                    },
                    {
                        text: "E-Commerce Website Integration",
                        available: true
                    },
                    {
                        text: "1000 Invoices (1 Month)",
                        available: true
                    },
                    {
                        text: "No. Of User - 5",
                        available: true
                    },
                    {
                        text: "No. Of Branch - 1",
                        available: true
                    },
                    {
                        text: "Online Training & Support",
                        available: true
                    }
                ]
            },
            {
                name: "ENTERPRISE",
                header: "header-enterprise",
                button: "Subscribe Now",
                prices: {
                    1: {
                        old: "30000",
                        new: "25500",
                        duration: "Year"
                    },
                    3: {
                        old: "72000",
                        new: "62000",
                        duration: "3 Months"
                    },
                    6: {
                        old: "140000",
                        new: "120000",
                        duration: "6 Months"
                    }
                },
                features: [{
                        text: "Sale, Purchase Management",
                        available: true
                    },
                    {
                        text: "Accounts Management",
                        available: true
                    },
                    {
                        text: "Inventory Management",
                        available: true
                    },
                    {
                        text: "Order Management",
                        available: true
                    },
                    {
                        text: "E-Commerce Website Integration",
                        available: true
                    },
                    {
                        text: "5000 Invoices (1 Month)",
                        available: true
                    },
                    {
                        text: "No. Of User - 10",
                        available: true
                    },
                    {
                        text: "No. Of Branch - 2",
                        available: true
                    },
                    {
                        text: "Online Training & Support",
                        available: true
                    }
                ]
            }
        ];

        // Language Switcher Event Listener
        const languageSelect = document.getElementById("languageSelect");
        if (languageSelect) {
            languageSelect.addEventListener("change", function() {
                if (this.value === "bn") {
                    console.log("Bangla selected");
                } else {
                    console.log("English selected");
                }
            });
        }

        // Navbar Scroll Effect
        const navbar = document.getElementById("navbar");
        const topBar = document.getElementById("topBar");
        const navWrapper = document.getElementById("navWrapper");

        window.addEventListener("scroll", () => {
            if (!navbar) return;
            if (window.scrollY > 50) {
                if (topBar) topBar.classList.add("hidden");
                navbar.classList.add("bg-white", "shadow-lg");
                navbar.classList.remove("bg-transparent");
                if (navWrapper) navWrapper.classList.add("bg-white");
            } else {
                if (topBar) topBar.classList.remove("hidden");
                navbar.classList.remove("bg-white", "shadow-lg");
                navbar.classList.add("bg-transparent");
                if (navWrapper) navWrapper.classList.remove("bg-white");
            }
        });

        // Swiper Initialization
        let pricingSwiper;

        function initPricingSlider() {
            if (window.innerWidth >= 1280) {
                if (pricingSwiper) {
                    pricingSwiper.destroy(true, true);
                    pricingSwiper = null;
                }
                return;
            }

            if (pricingSwiper) return;

            pricingSwiper = new Swiper(".pricingSlider", {
                slidesPerView: 1,
                spaceBetween: 24,
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    }
                },
                pagination: {
                    el: ".pricingSlider .swiper-pagination",
                    clickable: true
                }
            });
        }

        let selectedDuration = 1;

        // Render Pricing Cards Function
        function renderPricingCards() {
            const container = document.getElementById("pricingCards");
            if (!container) return;

            container.innerHTML = ""; // Clear existing content

            pricingData.forEach(plan => {
                const price = plan.fixed ?
                    plan.prices[1] :
                    plan.prices[selectedDuration];

                let features = "";
                plan.features.forEach(feature => {
                    features += `
                <div class="feature ${feature.available ? "active" : "inactive"}">
                    <span>${feature.text}</span>
                </div>
            `;
                });

                container.innerHTML += `
        <div class="swiper-slide">
            <div class="pricing-card">

                ${plan.popular ? `<div class="ribbon">POPULAR</div>` : ""}

                <div class="pricing-header ${plan.header}">
                    <h3 class="text-xl font-bold uppercase mb-2 text-slate-800">
                        ${plan.name}
                    </h3>

                    <div class="old-price-wrapper">
                        ${price.old
                            ? `<span class="old-price">৳ ${Number(price.old).toLocaleString()}</span>`
                            : `<span class="old-price opacity-0">৳ 0</span>`
                        }
                    </div>

                    <div class="price-wrapper">
                        <span class="new-price">
                            <span class="currency">৳</span>${Number(price.new).toLocaleString()}
                        </span>
                        <span class="duration">/ ${price.duration}</span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="space-y-3 mb-6">
                        ${features}
                    </div>
                    
                    <button class="plan-btn">
                        ${plan.button}
                    </button>
                </div>

            </div>
        </div>
        `;
            });

            initPricingSlider();
        }

        // Initial Render and Resize Handler
        renderPricingCards();
        window.addEventListener("resize", () => {
            initPricingSlider();
        });

        // Billing Toggle Buttons
        const billingButtons = document.querySelectorAll(".billing-btn");
        billingButtons.forEach(button => {
            button.addEventListener("click", function() {
                selectedDuration = Number(this.dataset.duration);
                billingButtons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");
                renderPricingCards();
            });
        });
        const backToTop = document.getElementById("backToTop");

        if (backToTop) {
            window.addEventListener("scroll", () => {
                // window.innerHeight equals exactly 100vh
                if (window.scrollY >= window.innerHeight) {
                    backToTop.classList.add("show");
                    backToTop.classList.remove("hidden"); // Ensures visibility if hidden by default
                } else {
                    backToTop.classList.remove("show");
                    backToTop.classList.add("hidden");
                }
            });

            backToTop.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        }

        // Mobile Menu Toggle
        const menuBtn = document.getElementById("menuBtn");
        const mobileMenu = document.getElementById("mobileMenu");

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
                menuBtn.innerHTML = mobileMenu.classList.contains("hidden") ?
                    '<i class="ri-menu-3-line"></i>' :
                    '<i class="ri-close-line"></i>';
            });
        }

        // Partner Logo Slider
        new Swiper(".partnerSlider", {
            loop: true,
            speed: 4000,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
            spaceBetween: 3,
            breakpoints: {
                320: {
                    slidesPerView: 2
                },
                640: {
                    slidesPerView: 3
                },
                768: {
                    slidesPerView: 4
                },
                1024: {
                    slidesPerView: 5
                }
            }
        });
    </script>

</html>
