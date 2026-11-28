{{-- ================= TERMS & CONDITIONS MODAL ================= --}}
<div id="termsConditionsModal" class="fixed inset-0 z-[9999] invisible opacity-0 transition-all duration-300"
    role="dialog" aria-modal="true" aria-labelledby="termsConditionsTitle" aria-hidden="true">

    {{-- Overlay --}}
    <div id="termsConditionsOverlay" class="absolute inset-0 bg-slate-950/80 backdrop-blur-md">
    </div>

    {{-- Modal Wrapper --}}
    <div class="relative flex min-h-full items-center justify-center p-3 sm:p-5">

        {{-- Modal Content --}}
        <div id="termsConditionsContent" tabindex="-1"
            class="relative flex w-full max-w-5xl max-h-[92vh] flex-col overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-2xl scale-95 translate-y-5 transition-all duration-300">

            {{-- ================= HEADER ================= --}}
            <div
                class="relative shrink-0 overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950 px-5 py-8 sm:px-10 sm:py-10">

                {{-- Background Decoration --}}
                <div class="absolute -top-24 -right-20 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>

                <div class="relative z-10">

                    {{-- Close Button --}}
                    <button type="button" id="closeTermsConditions" aria-label="Close Terms and Conditions"
                        class="absolute -top-3 -right-2 sm:top-0 sm:right-0 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 hover:rotate-90 transition-all duration-300">

                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>

                    {{-- Brand --}}
                    <div class="flex items-center gap-3 mb-6">

                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 shadow-lg shadow-cyan-500/20">
                            <i class="fa-solid fa-file-contract text-2xl text-white"></i>
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
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-cyan-400/30 bg-cyan-400/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-cyan-300">

                        <i class="fa-solid fa-scale-balanced"></i>
                        Terms of Service

                    </span>

                    <h2 id="termsConditionsTitle"
                        class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-white">

                        Terms &
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-400">
                            Conditions
                        </span>

                    </h2>

                    <p class="mt-4 max-w-2xl text-sm sm:text-base leading-7 text-slate-300">
                        Please read these terms carefully before accessing
                        or using BIMS services.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-slate-300">

                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2">
                            <i class="fa-solid fa-file-signature text-cyan-400"></i>
                            Service Agreement
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2">
                            <i class="fa-solid fa-handshake text-cyan-400"></i>
                            User Responsibilities
                        </span>

                    </div>

                </div>
            </div>


            {{-- ================= MODAL BODY ================= --}}
            <div class="flex-1 overflow-y-auto overscroll-contain bg-slate-50">

                <div class="px-5 py-8 sm:px-10 sm:py-10">

                    {{-- Introduction --}}
                    <section class="mb-10">

                        <div
                            class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-blue-50 p-5 sm:p-7">

                            <div class="flex items-center gap-3 mb-4">

                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-cyan-700 shadow-sm">
                                    <i class="fa-solid fa-file-circle-check text-xl"></i>
                                </div>

                                <h3 class="text-xl font-extrabold text-slate-900">
                                    Introduction
                                </h3>

                            </div>

                            <p class="text-sm sm:text-base leading-8 text-slate-600">
                                Welcome to <strong class="text-slate-900">BIMS
                                    (Business Integration Management System)</strong>,
                                developed by COMITS.
                            </p>

                            <p class="mt-3 text-sm sm:text-base leading-8 text-slate-600">
                                These Terms & Conditions govern your access to
                                and use of the BIMS website, software platform,
                                features, and related services.
                            </p>

                            <p class="mt-3 text-sm sm:text-base leading-8 text-slate-600">
                                By registering for an account, purchasing a
                                subscription, or using BIMS, you acknowledge
                                that you have read and understood these terms
                                and agree to be bound by them.
                            </p>

                        </div>

                    </section>


                    {{-- Terms Sections --}}
                    <div class="space-y-8">

                        {{-- 01 Acceptance --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                    <i class="fa-solid fa-handshake text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        01. Acceptance of Terms
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        By accessing or using BIMS, you agree
                                        to comply with these Terms & Conditions
                                        and our Privacy Policy.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        If you do not agree with any part of
                                        these terms, you should not use the
                                        platform or its services.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 02 Services --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                    <i class="fa-solid fa-layer-group text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        02. BIMS Services
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        BIMS is a business management software
                                        platform designed to help businesses
                                        organize and manage their operational
                                        activities.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Depending on the selected package and
                                        available features, the platform may
                                        provide:
                                    </p>

                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">

                                        @php
                                            $services = [
                                                ['icon' => 'fa-box-open', 'name' => 'Product Management'],
                                                ['icon' => 'fa-warehouse', 'name' => 'Inventory Management'],
                                                ['icon' => 'fa-cart-shopping', 'name' => 'Purchase Management'],
                                                ['icon' => 'fa-file-invoice-dollar', 'name' => 'Sales & Billing'],
                                                ['icon' => 'fa-chart-pie', 'name' => 'Business Reports'],
                                                ['icon' => 'fa-building', 'name' => 'Company & Branch Management'],
                                            ];
                                        @endphp

                                        @foreach ($services as $service)
                                            <div
                                                class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3">
                                                <i class="fa-solid {{ $service['icon'] }} text-cyan-600"></i>
                                                <span class="text-sm font-medium text-slate-700">
                                                    {{ $service['name'] }}
                                                </span>
                                            </div>
                                        @endforeach

                                    </div>

                                    <p class="mt-4 text-sm leading-7 text-slate-600">
                                        We reserve the right to improve, modify,
                                        add, or discontinue features as part
                                        of ongoing product development, subject
                                        to applicable contractual obligations.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 03 Account --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-user-lock text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        03. User Account & Registration
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        To access certain BIMS features, you
                                        may need to register and maintain
                                        an account.
                                    </p>

                                    <p class="mt-3 text-sm font-semibold text-slate-800">
                                        You agree to:
                                    </p>

                                    <ul class="mt-3 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>Provide accurate and complete registration information.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>Keep your login credentials confidential.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>Maintain the accuracy of your account information.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>Notify us promptly about suspected unauthorized account access.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>Ensure that users accessing your company account have appropriate
                                                authorization.</span>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 04 Subscription --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                                    <i class="fa-solid fa-cubes-stacked text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        04. Subscription & Packages
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        BIMS may offer different subscription
                                        packages with different features,
                                        usage limits, and service periods.
                                    </p>

                                    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5">

                                        <h4 class="font-bold text-slate-800">
                                            Subscription Conditions
                                        </h4>

                                        <ul class="mt-3 space-y-3 text-sm text-slate-600">

                                            <li class="flex gap-3">
                                                <i class="fa-solid fa-check-circle mt-1 text-purple-600"></i>
                                                <span>Each package provides the features and limits described at the
                                                    time of purchase.</span>
                                            </li>

                                            <li class="flex gap-3">
                                                <i class="fa-solid fa-check-circle mt-1 text-purple-600"></i>
                                                <span>Package limits may apply to companies, branches, users, customers,
                                                    suppliers, products, sales orders, and storage.</span>
                                            </li>

                                            <li class="flex gap-3">
                                                <i class="fa-solid fa-check-circle mt-1 text-purple-600"></i>
                                                <span>Access to subscription features may depend on the active
                                                    subscription period.</span>
                                            </li>

                                            <li class="flex gap-3">
                                                <i class="fa-solid fa-check-circle mt-1 text-purple-600"></i>
                                                <span>Upgrades, renewals, and package changes are subject to the
                                                    available subscription options.</span>
                                            </li>

                                        </ul>

                                    </div>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        We will communicate applicable package
                                        changes and pricing updates through
                                        appropriate channels.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 05 Payment --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                                    <i class="fa-solid fa-credit-card text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        05. Payment & Billing
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        Paid BIMS subscriptions must be
                                        purchased using the payment methods
                                        made available on the platform.
                                    </p>

                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-orange-600"></i>
                                            <span>Subscription prices and applicable charges will be displayed before
                                                purchase.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-orange-600"></i>
                                            <span>Payments are subject to confirmation from the relevant payment
                                                provider.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-orange-600"></i>
                                            <span>Users are responsible for providing correct billing and payment
                                                information.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-orange-600"></i>
                                            <span>Applicable taxes, fees, and charges will be handled according to the
                                                displayed pricing and applicable requirements.</span>
                                        </li>

                                    </ul>

                                    <p class="mt-4 text-sm leading-7 text-slate-600">
                                        Any refund or cancellation request
                                        will be handled according to our
                                        applicable Refund & Cancellation Policy.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 06 Responsibilities --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                    <i class="fa-solid fa-list-check text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        06. User Responsibilities
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        Users are responsible for their use
                                        of BIMS and the information entered
                                        into their accounts.
                                    </p>

                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-teal-600"></i>
                                            <span>Maintain accurate business and transaction records.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-teal-600"></i>
                                            <span>Ensure appropriate access permissions for employees and authorized
                                                users.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-teal-600"></i>
                                            <span>Maintain independent backups of important business records where
                                                appropriate.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-teal-600"></i>
                                            <span>Use BIMS in compliance with applicable laws and regulations.</span>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 07 Acceptable Use --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                                    <i class="fa-solid fa-ban text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        07. Acceptable Use Policy
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        You must not use BIMS for unlawful,
                                        harmful, fraudulent, or unauthorized
                                        activities.
                                    </p>

                                    <p class="mt-3 text-sm font-semibold text-slate-800">
                                        Prohibited activities include:
                                    </p>

                                    <ul class="mt-3 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-xmark mt-1 text-rose-600"></i>
                                            <span>Attempting unauthorized access to accounts, systems, or
                                                infrastructure.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-xmark mt-1 text-rose-600"></i>
                                            <span>Uploading malicious code, malware, or harmful content.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-xmark mt-1 text-rose-600"></i>
                                            <span>Interfering with platform security, availability, or
                                                performance.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-xmark mt-1 text-rose-600"></i>
                                            <span>Using the platform to violate the rights of other individuals or
                                                organizations.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-xmark mt-1 text-rose-600"></i>
                                            <span>Copying, reverse engineering, or exploiting the platform except where
                                                permitted by applicable law.</span>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 08 Data Ownership --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                    <i class="fa-solid fa-database text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        08. Data Ownership & Privacy
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        Subject to applicable rights and
                                        obligations, users retain their
                                        rights to the business information
                                        and data they submit to BIMS.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        COMITS does not claim ownership of
                                        customer business records simply
                                        because they are stored or processed
                                        through the platform.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        We process information as necessary
                                        to provide, secure, and improve the
                                        services, in accordance with our
                                        Privacy Policy and applicable
                                        requirements.
                                    </p>

                                    <a href="#"
                                        class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-cyan-700 hover:text-cyan-900">

                                        Read our Privacy Policy
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>

                                    </a>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 09 Intellectual Property --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                    <i class="fa-solid fa-copyright text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        09. Intellectual Property
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        The BIMS platform, including its
                                        software, source code, design,
                                        interface, branding, logos,
                                        documentation, and related materials,
                                        is owned by or licensed to COMITS
                                        and is protected by applicable
                                        intellectual property laws.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Users are granted a limited,
                                        non-exclusive, non-transferable
                                        right to access and use BIMS
                                        according to their subscription
                                        and these terms.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        No ownership rights in the software
                                        are transferred to users through
                                        subscription or access.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 10 Availability --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                                    <i class="fa-solid fa-server text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        10. Service Availability & Maintenance
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We aim to maintain reliable access
                                        to BIMS. However, uninterrupted
                                        availability cannot be guaranteed.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Access may occasionally be affected
                                        by scheduled maintenance, software
                                        updates, infrastructure issues,
                                        security incidents, third-party
                                        service disruptions, or circumstances
                                        beyond our reasonable control.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 11 Liability --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                                    <i class="fa-solid fa-scale-balanced text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        11. Limitation of Liability
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        BIMS is provided as a business
                                        management tool. Users remain
                                        responsible for verifying their
                                        business records, calculations,
                                        reports, and decisions made using
                                        information generated by the platform.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        To the extent permitted by applicable
                                        law, COMITS will not be liable for
                                        indirect, incidental, or consequential
                                        losses arising from the use of the
                                        platform.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Nothing in these terms excludes
                                        liability that cannot legally be
                                        excluded under applicable law.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 12 Suspension --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                                    <i class="fa-solid fa-user-slash text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        12. Suspension & Termination
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We may restrict, suspend, or terminate
                                        access to BIMS where reasonably
                                        necessary, including when:
                                    </p>

                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-rose-600"></i>
                                            <span>These terms are materially violated.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-rose-600"></i>
                                            <span>There is suspected fraud, abuse, or unauthorized access.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-rose-600"></i>
                                            <span>A subscription expires or payment obligations remain
                                                unresolved.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-rose-600"></i>
                                            <span>Required by law or necessary to protect the platform and its
                                                users.</span>
                                        </li>

                                    </ul>

                                    <p class="mt-4 text-sm leading-7 text-slate-600">
                                        Where reasonably possible and legally
                                        permitted, we will provide notice
                                        before taking such action.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 13 Changes --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                    <i class="fa-solid fa-arrows-rotate text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        13. Changes to These Terms
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We may update these Terms &
                                        Conditions from time to time
                                        to reflect changes in our services,
                                        operations, or applicable legal
                                        requirements.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Updated terms will be published
                                        on our website. Where appropriate,
                                        we may notify users of material
                                        changes through the platform
                                        or other communication channels.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 14 Governing Law --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-gavel text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        14. Governing Law & Jurisdiction
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        These terms are intended to be
                                        interpreted in accordance with
                                        the laws of Bangladesh, subject
                                        to applicable mandatory legal
                                        requirements.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Any dispute arising in connection
                                        with these terms will be handled
                                        by the competent courts or
                                        authorities having jurisdiction
                                        under applicable law.
                                    </p>

                                </div>

                            </div>

                        </section>

                    </div>


                    {{-- Contact Section --}}
                    <section class="mt-12">

                        <div
                            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-cyan-950 p-6 sm:p-8">

                            <div class="absolute -right-12 -bottom-20 h-56 w-56 rounded-full bg-cyan-400/10 blur-2xl">
                            </div>

                            <div class="relative z-10">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-400/15 text-cyan-300">
                                    <i class="fa-solid fa-headset text-xl"></i>
                                </div>

                                <h3 class="mt-5 text-2xl font-extrabold text-white">
                                    Contact Us
                                </h3>

                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    If you have any questions regarding
                                    these Terms & Conditions or need
                                    assistance with BIMS, please contact
                                    our team.
                                </p>

                                {{-- Update with your official contact details --}}
                                <div class="mt-6 space-y-3">
                                    <a href="mailto:sales@comitsbd.com"
                                        class="flex items-center gap-3 text-sm text-cyan-300 hover:text-white transition-colors">
                                        <i class="fa-solid fa-envelope"></i>
                                        sales@comitsbd.com
                                    </a>
                                    <a href="https://comitsbd.com" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-3 text-sm text-cyan-300 hover:text-white transition-colors">

                                        <i class="fa-solid fa-globe"></i>
                                        www.comitsbd.com
                                    </a>
                                </div>
                                <p class="mt-6 border-t border-white/10 pt-5 text-xs text-slate-400">
                                    BIMS — Business Integration Management System
                                    <br>
                                    Developed by COMITS
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
                    <button type="button" id="closeTermsConditionsBottom"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition-all duration-300 hover:bg-cyan-600">
                        <i class="fa-solid fa-check"></i>
                        Got It
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ================= END TERMS & CONDITIONS MODAL ================= --}}
