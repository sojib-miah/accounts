<!-- FOOTER START -->
<footer class="bg-gradient-to-b from-cyan-50 to-white text-slate-700 pt-16 border-t border-slate-200/80 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-8">

            <!-- NEWSLETTER -->
            <div class="lg:col-span-4 space-y-4">
                <div class="inline-block">
                    <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">
                        {{ __('common.newsletter') }}</h3>
                    <div class="h-1 w-12 bg-cyan-600 rounded-sm mt-1"></div>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed text-justify">
                    Innovative IT solutions, web & mobile apps, and digital growth — BIMS empowers
                    your
                    business for the future.
                </p>
                <form onsubmit="event.preventDefault();" class="mt-4">
                    <div
                        class="flex items-center rounded-lg border border-slate-300 overflow-hidden bg-white focus-within:border-cyan-600 focus-within:ring-1 focus-within:ring-cyan-600 shadow-sm transition">
                        <input type="email" placeholder="Email Address"
                            class="w-full py-3 px-4 text-sm text-slate-800 focus:outline-none placeholder:text-slate-400"
                            required />
                        <button type="submit"
                            class="bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-3.5 transition-colors flex items-center justify-center"
                            aria-label="Subscribe">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SERVICES -->
            <div class="lg:col-span-2 space-y-4">
                <div class="inline-block">
                    <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">
                        {{ __('common.services') }}</h3>
                    <div class="h-1 w-10 bg-cyan-600 rounded-sm mt-1"></div>
                </div>
                <ul class="space-y-2 text-sm">
                    <li>
                        <button type="button" id="implementationBtn"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Implementation</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" id="dataMigrationBtn"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Data Migration</span>
                        </button>
                    </li>

                    <li>
                        <button type="button" id="userTrainingBtn"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>User Training</span>
                        </button>
                    </li>

                    <li>
                        <button type="button" id="technicalSupportBtn"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Technical Support</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" id="customizationBtn"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Customization</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" id="maintenanceBtn"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Maintenance</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- COMPANY -->
            <div class="lg:col-span-2 space-y-4">
                <div class="inline-block">
                    <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">
                        {{ __('common.company') }}</h3>
                    <div class="h-1 w-10 bg-cyan-600 rounded-sm mt-1"></div>
                </div>
                <ul class="space-y-2 text-sm">
                    <li>
                        <button type="button" id="aboutUsBtn" aria-haspopup="dialog" aria-controls="aboutUsModal"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors duration-300">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>About Us</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" id="privacyPolicyBtn" aria-haspopup="dialog"
                            aria-controls="privacyPolicyModal"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Privacy Policy</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" id="termsConditionsBtn" aria-haspopup="dialog"
                            aria-controls="termsConditionsModal"
                            class="group flex w-full items-center text-left text-slate-600 hover:text-cyan-600 transition-colors">
                            <i
                                class="fa-solid fa-caret-right text-cyan-600 text-xs w-4 shrink-0 transition-transform group-hover:translate-x-1"></i>
                            <span>Terms & Conditions</span>
                        </button>
                    </li>
                </ul>
            </div>
            <!-- CONTACT DETAILS -->
            <div class="lg:col-span-4 space-y-4">
                <div class="inline-block">
                    <h3 class="text-lg font-bold uppercase tracking-wider text-slate-900">
                        {{ __('common.contact_details') }}
                    </h3>
                    <div class="h-1 w-12 bg-cyan-600 rounded-full mt-1"></div>
                </div>

                <div class="space-y-4 text-sm text-slate-600">
                    <!-- Address (Centered) -->
                    <div class="flex items-center gap-3.5 group">
                        <div
                            class="w-10 h-10 shrink-0 rounded-xl bg-red-50 border border-red-100/80 flex items-center justify-center text-red-600 shadow-xs group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-location-dot text-base"></i>
                        </div>
                        <span class="leading-relaxed text-slate-700">
                            3<sup>rd</sup> floor,
                            House-375, Road-28
                            DOHS Mohakhali
                            Dhaka-1212, Bangladesh
                        </span>
                    </div>

                    <!-- Phone (Centered) -->
                    <div class="flex items-center gap-3.5 group">
                        <div
                            class="w-10 h-10 shrink-0 rounded-xl bg-green-50 border border-green-100/80 flex items-center justify-center text-green-600 shadow-xs group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </div>
                        <div class="space-y-1 leading-snug">
                            <p><span class="font-semibold text-slate-800">Sales:</span> &nbsp;&nbsp;
                                &nbsp; <a href="tel:+8801711257224"
                                    class="hover:text-cyan-600 transition">+8801711257224</a></p>
                            <p><span class="font-semibold text-slate-800">Support: </span> <a href="tel:+8801331995522"
                                    class="hover:text-cyan-600 transition">
                                    +8801331995522</a></p>
                        </div>
                    </div>

                    <!-- Email (Centered) -->
                    <div class="flex items-center gap-3.5 group">
                        <div
                            class="w-10 h-10 shrink-0 rounded-xl bg-cyan-50 border border-cyan-100/80 flex items-center justify-center text-cyan-600 shadow-xs group-hover:bg-cyan-600 group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <div class="space-y-1 leading-snug">
                            <p><a href="mailto:sales@bims.bd" class="hover:text-cyan-600 transition">sales@bims.bd</a>
                            </p>
                            {{-- <p><a href="mailto:support@comitsbd.com"
                                                class="hover:text-cyan-600 transition">sales@comitsbd.com</a></p> --}}
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
                Copyright &copy; {{ date('Y') }} <a href="https://comitsbd.com" target="_blank"
                    class="text-cyan-400 font-medium hover:underline">Comits Soft</a>. All
                rights reserved. <small>({{ $totalVisitors }} unique visitors, {{ $todayVisitors }}
                    today)</small>
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
    <a href="mailto:sales@bims.bd"
        class="bg-slate-600 text-white p-3 rounded-r-md flex items-center justify-center shadow-md transition-transform duration-300 -translate-x-2 hover:translate-x-0">
        <i class="fa-solid fa-envelope text-lg"></i>
    </a>
</div>

<button id="backToTop" type="button" aria-label="Back to top" title="Back to top">
    <i class="fa-solid fa-arrow-up text-lg"></i>
</button>
