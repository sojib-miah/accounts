<header id="navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent transition-all duration-300">
    <!-- TOP BAR (Will hide on scroll) -->
    <div id="topBar" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300 overflow-hidden py-2">
        <div class="flex justify-between sm:justify-end items-center h-10 gap-4 sm:gap-8">
            <!-- Phone Number -->
            <div class="flex items-center gap-1.5 sm:gap-2">
                <i class="fa-solid fa-phone text-cyan-500 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm md:text-base font-medium text-slate-600">
                    {{ __('common.phone_one') }}
                </span>
            </div>
            <!-- Language Selector -->
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1 shadow-sm">
                <i class="fa-solid fa-globe text-emerald-500 text-sm"></i>
                <select id="languageSelect" onchange="switchLanguage(this.value)"
                    class="cursor-pointer appearance-none border-0 bg-transparent py-1 pr-5 text-sm font-semibold text-slate-700 outline-none focus:ring-0">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                        🇬🇧 English
                    </option>
                    <option value="bn" {{ app()->getLocale() === 'bn' ? 'selected' : '' }}>
                        🇧🇩 বাংলা
                    </option>
                </select>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR (Stays visible) -->
    <div id="mainNav" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300">
        <div class="flex items-center justify-between py-2 sm:py-3">
            <!-- Logo -->
            <a href="#" class="flex-shrink-0">
                @if (optional(setting())->logo)
                    <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : '' }}"
                        class="h-12 sm:h-11 md:h-12 w-full object-contain" alt="COMITS">
                @else
                    <img src="{{ asset('uploads/logo.png') }}" class="h-12 sm:h-11 md:h-12 w-full object-contain"
                        alt="COMITS">
                @endif
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-8 xl:gap-10">
                <a href="{{ route('home') }}"
                    class="font-medium text-slate-700 hover:text-cyan-600 transition">{{ __('common.home') }}</a>
                <a href="#features"
                    class="font-medium text-slate-700 hover:text-cyan-500 transition">{{ __('common.features') }}</a>
                <a href="#pricing"
                    class="font-medium text-slate-700 hover:text-cyan-500 transition">{{ __('common.packages') }}</a>
                <a href="#contact"
                    class="font-medium text-slate-700 hover:text-cyan-500 transition">{{ __('common.contact') }}</a>
            </nav>

            @if (Auth::user())
                <a href="{{ route('dashboard.index') }}"
                    class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all duration-300 shadow-sm hover:shadow">
                    {{ __('common.go_to_dashboard') }}
                </a>
            @else
                <!-- Desktop Action Buttons -->
                <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                    <a href="{{ route('admin.login') }}"
                        class="px-5 py-2 border border-cyan-600 rounded-xl text-cyan-600 font-semibold hover:bg-cyan-500 hover:text-white transition-all duration-300">
                        {{ __('common.login') }}
                    </a>
                    <a href="{{ route('admin.register') }}"
                        class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all duration-300 shadow-sm hover:shadow">
                        {{ __('common.free_trial') }}
                    </a>
                </div>
            @endif

            <!-- Mobile Hamburger Button -->
            <button id="menuBtn" class="lg:hidden text-2xl sm:text-3xl text-slate-800 p-1 focus:outline-none"
                aria-label="Toggle menu" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU DROPDOWN -->
    <div id="mobileMenu" class="hidden lg:hidden bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-xl">
        <div class="flex flex-col p-5 gap-3.5 text-slate-700 font-medium">
            <a href="#" class="py-1 hover:text-cyan-600 transition">{{ __('common.home') }}</a>
            <a href="#features" class="py-1 hover:text-cyan-500 transition">{{ __('common.features') }}</a>
            <a href="#pricing" class="py-1 hover:text-cyan-500 transition">{{ __('common.packages') }}</a>
            <a href="#contact" class="py-1 hover:text-cyan-500 transition">{{ __('common.contact') }}</a>
            <hr class="border-slate-100 my-1">
            @if (Auth::user())
                <a href="{{ route('dashboard.index') }}"
                    class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all duration-300 shadow-sm hover:shadow">
                    {{ __('common.go_to_dashboard') }}
                </a>
            @else
                <div class="flex flex-col gap-2 pt-1">
                    <a href="{{ route('admin.login') }}"
                        class="w-full border text-center border-cyan-600 text-cyan-500 font-semibold rounded-lg py-2.5 hover:bg-cyan-50 transition">
                        {{ __('common.login') }}
                    </a>
                    <a href="{{ route('admin.register') }}"
                        class="w-full bg-emerald-600 text-center text-white font-semibold rounded-lg py-2.5 hover:bg-emerald-700 transition">
                        {{ __('common.free_trial') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>
