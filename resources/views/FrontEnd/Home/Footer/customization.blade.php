<!-- Customization Modal -->
<div id="customizationModal"
    class="fixed inset-0 z-[9999] invisible opacity-0
           flex items-center justify-center p-3 sm:p-5
           transition-all duration-300">

    <!-- Background Overlay -->
    <div id="customizationOverlay" class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm">
    </div>

    <!-- Modal Content -->
    <div id="customizationContent"
        class="relative w-full max-w-3xl max-h-[90vh]
               overflow-y-auto rounded-2xl sm:rounded-3xl
               bg-white shadow-2xl
               scale-95 translate-y-5
               transition-all duration-300">

        <!-- Header -->
        <div
            class="relative overflow-hidden bg-gradient-to-br
                    from-cyan-600 via-cyan-500 to-blue-600
                    px-5 py-8 sm:px-8 sm:py-10 text-white">

            <!-- Decorative Circles -->
            <div
                class="absolute -right-12 -top-16 h-48 w-48
                        rounded-full border-[25px] border-white/10">
            </div>

            <div class="absolute -bottom-16 right-24 h-36 w-36
                        rounded-full bg-white/10">
            </div>

            <!-- Close Button -->
            <button id="closeCustomization" type="button" aria-label="Close modal"
                class="absolute right-4 top-4 z-10 flex h-9 w-9
                       items-center justify-center rounded-full
                       bg-white/20 text-white transition
                       hover:bg-white/30 hover:rotate-90">

                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="relative z-10">

                <!-- Icon -->
                <div
                    class="mb-5 flex h-14 w-14 items-center
                            justify-center rounded-2xl
                            bg-white/20 shadow-lg backdrop-blur-sm">

                    <i class="fa-solid fa-sliders text-2xl"></i>
                </div>

                <span
                    class="inline-block rounded-full
                             border border-white/30 bg-white/15
                             px-3 py-1 text-xs font-semibold
                             uppercase tracking-wider">
                    Our Services
                </span>

                <h2
                    class="mt-4 text-2xl font-extrabold
                           leading-tight sm:text-3xl md:text-4xl">
                    Customization Services
                </h2>

                <p class="mt-3 max-w-xl text-sm leading-6
                          text-cyan-50 sm:text-base">
                    A Business Solution That Fits Your Requirements
                </p>

            </div>
        </div>

        <!-- Body -->
        <div class="px-5 py-6 sm:px-8 sm:py-8">

            <!-- Introduction -->
            <div class="mb-7">

                <div class="mb-3 flex items-center gap-2">
                    <span class="h-1 w-8 rounded-full bg-cyan-500"></span>

                    <span
                        class="text-xs font-bold uppercase
                                 tracking-widest text-cyan-600">
                        About Customization
                    </span>
                </div>

                <p class="text-sm leading-7 text-slate-600 sm:text-base">
                    No two businesses operate in exactly the same way.
                    Our Customization Services help adapt BIMS functionality
                    to better match your specific business processes
                    and operational requirements.
                </p>

                <p class="mt-3 text-sm leading-7 text-slate-600 sm:text-base">
                    If your business needs additional functionality or
                    changes to an existing workflow, our development
                    team can evaluate your requirements and propose
                    a suitable solution.
                </p>

            </div>

            <!-- What We Provide -->
            <div class="rounded-2xl border border-slate-100
                        bg-slate-50/80 p-4 sm:p-6">

                <h3 class="mb-5 flex items-center gap-3
                           text-lg font-bold text-slate-800">

                    <span
                        class="flex h-10 w-10 items-center
                                 justify-center rounded-xl
                                 bg-cyan-100 text-cyan-600">

                        <i class="fa-solid fa-list-check"></i>
                    </span>

                    What We Provide
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <!-- Item 1 -->
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Business-specific functionality adjustments
                        </p>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Custom fields and data requirements
                        </p>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Workflow and process customization
                        </p>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Custom reports and reporting formats
                        </p>
                    </div>

                    <!-- Item 5 -->
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Business-specific operational features
                        </p>
                    </div>

                    <!-- Item 6 -->
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Integration requirement assessment
                        </p>
                    </div>

                    <!-- Item 7 -->
                    <div class="flex items-start gap-3 sm:col-span-2">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center
                                     justify-center rounded-full
                                     bg-emerald-100 text-emerald-600">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>

                        <p class="text-sm leading-6 text-slate-600">
                            Custom software development based on
                            approved requirements
                        </p>
                    </div>

                </div>
            </div>

            <!-- Our Goal -->
            <div
                class="relative mt-6 overflow-hidden rounded-2xl
                        border border-cyan-100 bg-cyan-50 p-5 sm:p-6">

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center
                                justify-center rounded-xl
                                bg-cyan-500 text-white shadow-md
                                shadow-cyan-500/20">

                        <i class="fa-solid fa-bullseye text-xl"></i>
                    </div>
                    <div>
                        <h3 class="mb-2 font-bold text-slate-800">
                            Our Goal
                        </h3>
                        <p class="text-sm leading-7 text-slate-600">
                            Make BIMS more suitable for your business
                            by aligning its functionality with the
                            way you work.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div
                class="mt-7 flex flex-col-reverse gap-3
                        sm:flex-row sm:items-center sm:justify-between">

                <button id="closeCustomizationBottom" type="button"
                    class="rounded-xl border border-slate-200
                           px-5 py-3 text-sm font-semibold
                           text-slate-600 transition
                           hover:bg-slate-50">
                    <i class="fa fa-times"></i> Close
                </button>
                <a href="{{ route('admin.register') }}"
                    class="inline-flex items-center justify-center
                           gap-2 rounded-xl bg-gradient-to-r
                           from-cyan-600 to-blue-600 px-6 py-3
                           text-sm font-bold text-white shadow-lg
                           shadow-cyan-500/20 transition-all
                           hover:-translate-y-0.5 hover:shadow-xl">
                    Get Started Now
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>
