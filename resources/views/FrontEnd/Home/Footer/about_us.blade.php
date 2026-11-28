{{-- ================= ABOUT US MODAL ================= --}}
<div id="aboutUsModal" class="fixed inset-0 z-[9999] invisible opacity-0 transition-all duration-300" role="dialog"
    aria-modal="true" aria-labelledby="aboutUsTitle" aria-hidden="true">

    {{-- Overlay --}}
    <div id="aboutUsOverlay" class="absolute inset-0 bg-slate-950/80 backdrop-blur-md">
    </div>

    {{-- Modal Wrapper --}}
    <div class="relative flex min-h-full items-center justify-center p-3 sm:p-5">

        {{-- Modal Content --}}
        <div id="aboutUsContent" tabindex="-1"
            class="relative w-full max-w-5xl max-h-[92vh] overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-2xl shadow-black/30 scale-95 translate-y-5 transition-all duration-300 flex flex-col">

            {{-- ================= HEADER ================= --}}
            <div
                class="relative shrink-0 overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950 px-5 py-8 sm:px-10 sm:py-12">

                {{-- Background Decoration --}}
                <div class="absolute -top-24 -right-20 w-72 h-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-20 w-72 h-72 rounded-full bg-blue-500/10 blur-3xl"></div>

                <div class="relative z-10">

                    {{-- Close Button --}}
                    <button type="button" id="closeAboutUs" aria-label="Close About Us"
                        class="absolute -top-3 -right-2 sm:top-0 sm:right-0 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 hover:rotate-90 transition-all duration-300">

                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>

                    {{-- Brand --}}
                    <div class="flex items-center gap-3 mb-6">

                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 shadow-lg shadow-cyan-500/20">
                            <span class="text-2xl font-black text-white">B</span>
                        </div>

                        <div>
                            <h3 class="text-2xl font-extrabold tracking-wider text-white">
                                BIMS
                            </h3>
                            <p class="text-[10px] sm:text-xs font-medium tracking-[0.18em] text-cyan-300">
                                BUSINESS INTEGRATION MANAGEMENT SYSTEM
                            </p>
                        </div>

                    </div>

                    {{-- Heading --}}
                    <div class="max-w-3xl">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-cyan-400/30 bg-cyan-400/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-cyan-300">
                            <span class="h-2 w-2 rounded-full bg-cyan-400 animate-pulse"></span>
                            About Us
                        </span>

                        <h2 id="aboutUsTitle"
                            class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-white">

                            Empowering Businesses Through
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-400">
                                Smarter Management
                            </span>
                        </h2>

                        <p class="mt-5 max-w-2xl text-sm sm:text-base leading-7 text-slate-300">
                            Simplifying business operations with practical,
                            reliable, and intelligent management technology.
                        </p>

                    </div>

                    {{-- Bottom Stats / Highlights --}}
                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-3">

                        <div class="rounded-xl border border-white/10 bg-white/5 p-3 sm:p-4">
                            <i class="fa-solid fa-layer-group text-xl text-cyan-400"></i>
                            <p class="mt-2 text-sm font-bold text-white">
                                All-in-One
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                Business Management
                            </p>
                        </div>

                        <div class="rounded-xl border border-white/10 bg-white/5 p-3 sm:p-4">
                            <i class="fa-solid fa-chart-line text-xl text-cyan-400"></i>
                            <p class="mt-2 text-sm font-bold text-white">
                                Smarter Decisions
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                Better Business Insights
                            </p>
                        </div>

                        <div class="hidden sm:block rounded-xl border border-white/10 bg-white/5 p-4">
                            <i class="fa-solid fa-building text-xl text-cyan-400"></i>
                            <p class="mt-2 text-sm font-bold text-white">
                                Powered by COMITS
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                Technology & Innovation
                            </p>
                        </div>

                    </div>

                </div>
            </div>


            {{-- ================= MODAL BODY ================= --}}
            <div class="flex-1 overflow-y-auto overscroll-contain bg-slate-50">

                <div class="px-5 py-8 sm:px-10 sm:py-10">

                    {{-- Introduction --}}
                    <section class="mb-12">

                        <div class="flex items-center gap-3 mb-5">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                <i class="fa-solid fa-building text-lg"></i>
                            </div>

                            <div>
                                <span class="text-xs font-bold uppercase tracking-widest text-cyan-600">
                                    Our Story
                                </span>
                                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">
                                    Who We Are
                                </h3>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-7 shadow-sm">

                            <p class="text-sm sm:text-base leading-8 text-slate-600">
                                <strong class="text-slate-900">BIMS (Business Integration Management System)</strong>
                                is a business management software solution designed
                                to simplify everyday business operations and help
                                businesses work more efficiently.
                            </p>

                            <p class="mt-4 text-sm sm:text-base leading-8 text-slate-600">
                                We understand that managing products, inventory,
                                purchases, billing, and business reports can become
                                challenging as a business grows. BIMS brings essential
                                business activities together in one organized platform,
                                helping businesses improve operational visibility,
                                reduce manual work, and make better-informed decisions.
                            </p>

                            <p class="mt-4 text-sm sm:text-base leading-8 text-slate-600">
                                Our goal is to make business management simpler,
                                smarter, and more accessible through practical technology.
                            </p>

                        </div>

                    </section>


                    {{-- Mission & Vision --}}
                    <section class="mb-12">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Mission --}}
                            <div
                                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-600 to-blue-700 p-6 sm:p-7 text-white shadow-lg shadow-cyan-700/10">

                                <div
                                    class="absolute -right-10 -top-10 h-36 w-36 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-150">
                                </div>

                                <div class="relative z-10">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/15">
                                        <i class="fa-solid fa-bullseye text-2xl"></i>
                                    </div>

                                    <h3 class="mt-5 text-xl font-bold">
                                        Our Mission
                                    </h3>

                                    <p class="mt-3 text-sm leading-7 text-cyan-50">
                                        To simplify business management through reliable,
                                        user-friendly, and practical software solutions
                                        that help businesses improve productivity,
                                        maintain better control, and achieve
                                        sustainable growth.
                                    </p>

                                </div>
                            </div>


                            {{-- Vision --}}
                            <div
                                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-950 p-6 sm:p-7 text-white shadow-lg">

                                <div
                                    class="absolute -right-10 -top-10 h-36 w-36 rounded-full bg-cyan-400/10 transition-transform duration-500 group-hover:scale-150">
                                </div>

                                <div class="relative z-10">

                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-400/15 text-cyan-300">
                                        <i class="fa-solid fa-eye text-2xl"></i>
                                    </div>

                                    <h3 class="mt-5 text-xl font-bold">
                                        Our Vision
                                    </h3>

                                    <p class="mt-3 text-sm leading-7 text-slate-300">
                                        To become a trusted business management
                                        software brand, empowering businesses
                                        of different sizes to manage their operations
                                        more efficiently through innovative and
                                        accessible technology.
                                    </p>

                                </div>
                            </div>

                        </div>

                    </section>


                    {{-- What We Do --}}
                    <section class="mb-12">

                        <div class="mb-6">
                            <span class="text-xs font-bold uppercase tracking-widest text-cyan-600">
                                Our Solutions
                            </span>

                            <h3 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900">
                                What We Do
                            </h3>

                            <p class="mt-3 max-w-2xl text-sm sm:text-base leading-7 text-slate-500">
                                BIMS brings essential business management activities
                                together in one platform.
                            </p>
                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                            {{-- Product --}}
                            <div
                                class="group rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                    <i class="fa-solid fa-box-open text-xl"></i>
                                </div>

                                <h4 class="mt-4 font-bold text-slate-900">
                                    Product Management
                                </h4>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Organize and maintain product information.
                                </p>
                            </div>


                            {{-- Inventory --}}
                            <div
                                class="group rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                                    <i class="fa-solid fa-warehouse text-xl"></i>
                                </div>

                                <h4 class="mt-4 font-bold text-slate-900">
                                    Inventory Management
                                </h4>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Monitor stock levels and maintain better
                                    inventory control.
                                </p>
                            </div>


                            {{-- Purchase --}}
                            <div
                                class="group rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600 transition-colors group-hover:bg-orange-600 group-hover:text-white">
                                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                                </div>

                                <h4 class="mt-4 font-bold text-slate-900">
                                    Purchase Management
                                </h4>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Organize purchase transactions and
                                    related records.
                                </p>
                            </div>


                            {{-- Billing --}}
                            <div
                                class="group rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 transition-colors group-hover:bg-purple-600 group-hover:text-white">
                                    <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                                </div>

                                <h4 class="mt-4 font-bold text-slate-900">
                                    Billing Management
                                </h4>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Manage billing activities and
                                    transaction information.
                                </p>
                            </div>


                            {{-- Reports --}}
                            <div
                                class="group rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg sm:col-span-2 lg:col-span-1">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 transition-colors group-hover:bg-cyan-600 group-hover:text-white">
                                    <i class="fa-solid fa-chart-pie text-xl"></i>
                                </div>

                                <h4 class="mt-4 font-bold text-slate-900">
                                    Business Reports
                                </h4>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Access organized business information
                                    to support better decisions.
                                </p>
                            </div>

                        </div>

                        <p class="mt-5 text-sm leading-7 text-slate-500">
                            We continuously aim to improve the platform to address
                            evolving business requirements.
                        </p>

                    </section>


                    {{-- Why Choose BIMS --}}
                    <section class="mb-12">

                        <div class="mb-6">
                            <span class="text-xs font-bold uppercase tracking-widest text-cyan-600">
                                Our Commitment
                            </span>

                            <h3 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900">
                                Why Choose BIMS?
                            </h3>

                            <p class="mt-3 text-sm sm:text-base text-slate-500 leading-7">
                                We believe business software should make work easier,
                                not more complicated.
                            </p>
                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            @php
                                $benefits = [
                                    [
                                        'icon' => 'fa-face-smile',
                                        'title' => 'Easy to Use',
                                        'description' => 'A user-friendly approach to everyday business operations.',
                                        'color' => 'cyan',
                                    ],
                                    [
                                        'icon' => 'fa-layer-group',
                                        'title' => 'Organized Management',
                                        'description' => 'Keep essential business information in one platform.',
                                        'color' => 'blue',
                                    ],
                                    [
                                        'icon' => 'fa-shield-halved',
                                        'title' => 'Better Control',
                                        'description' =>
                                            'Improve visibility into products, inventory, purchases, and billing.',
                                        'color' => 'emerald',
                                    ],
                                    [
                                        'icon' => 'fa-clock',
                                        'title' => 'Time Efficiency',
                                        'description' => 'Reduce repetitive manual work and improve productivity.',
                                        'color' => 'orange',
                                    ],
                                    [
                                        'icon' => 'fa-briefcase',
                                        'title' => 'Business-Focused',
                                        'description' => 'Designed around practical business management needs.',
                                        'color' => 'purple',
                                    ],
                                    [
                                        'icon' => 'fa-arrows-rotate',
                                        'title' => 'Continuous Improvement',
                                        'description' =>
                                            'A commitment to improving the software as business requirements evolve.',
                                        'color' => 'pink',
                                    ],
                                ];
                            @endphp

                            @foreach ($benefits as $benefit)
                                <div
                                    class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:border-cyan-300 hover:shadow-md">

                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700">
                                        <i class="fa-solid {{ $benefit['icon'] }} text-lg"></i>
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-slate-900">
                                            {{ $benefit['title'] }}
                                        </h4>

                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            {{ $benefit['description'] }}
                                        </p>
                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </section>


                    {{-- Powered by COMITS --}}
                    <section class="mb-10">

                        <div
                            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 sm:p-8">

                            <div class="absolute -right-10 -bottom-20 h-60 w-60 rounded-full bg-cyan-500/10 blur-2xl">
                            </div>

                            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">

                                <div
                                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white shadow-lg">
                                    <span class="text-3xl font-black tracking-tight text-cyan-600">
                                        C
                                    </span>
                                </div>

                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-cyan-400">
                                        Powered by
                                    </span>

                                    <h3 class="mt-1 text-2xl font-extrabold text-white">
                                        COMITS
                                    </h3>

                                    <p class="mt-3 text-sm leading-7 text-slate-300">
                                        BIMS is proudly developed by COMITS, bringing
                                        software development knowledge and IT expertise
                                        together to deliver practical technology solutions.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-300">
                                        With a focus on innovation, quality, and customer
                                        satisfaction, COMITS aims to help businesses
                                        embrace digital transformation and build
                                        more efficient operations.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </section>


                    {{-- Final CTA --}}
                    <section>

                        <div
                            class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-blue-50 px-5 py-8 sm:px-8 sm:py-10 text-center">

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-cyan-600 shadow-sm">
                                <i class="fa-solid fa-rocket text-2xl"></i>
                            </div>

                            <h3 class="mt-5 text-2xl sm:text-3xl font-extrabold text-slate-900">
                                Building Better Businesses Together
                            </h3>

                            <p class="mx-auto mt-4 max-w-2xl text-sm sm:text-base leading-8 text-slate-600">
                                Every successful business needs the right tools to grow.
                                BIMS is here to make business management more organized,
                                efficient, and convenient.
                            </p>
                            <p class="mx-auto mt-3 max-w-2xl text-sm sm:text-base leading-8 text-slate-600">
                                Whether you are starting a new business or improving
                                your existing operations, BIMS aims to support your
                                journey toward smarter business management.
                            </p>

                            <div class="mt-7">
                                <p class="text-lg sm:text-xl font-extrabold text-slate-900">
                                    BIMS — Business Integration Management System
                                </p>
                                <p class="mt-2 text-base font-bold text-cyan-700">
                                    Simplify Business. Amplify Growth.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            {{-- ================= FOOTER ================= --}}
            <div class="shrink-0 border-t border-slate-200 bg-white px-5 py-4 sm:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-xs text-slate-500 text-center sm:text-left">
                        © {{ date('Y') }} BIMS. Developed by
                        <span class="font-bold text-slate-800">COMITS</span>.
                    </p>
                    <button type="button" id="closeAboutUsBottom"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition-all duration-300 hover:bg-cyan-600">
                        <i class="fa-solid fa-check"></i>
                        Got It
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>
{{-- ================= END ABOUT US MODAL ================= --}}
