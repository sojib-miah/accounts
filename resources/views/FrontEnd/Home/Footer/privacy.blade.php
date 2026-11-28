{{-- ================= PRIVACY POLICY MODAL ================= --}}
<div id="privacyPolicyModal" class="fixed inset-0 z-[9999] invisible opacity-0 transition-all duration-300" role="dialog"
    aria-modal="true" aria-labelledby="privacyPolicyTitle" aria-hidden="true">

    {{-- Overlay --}}
    <div id="privacyPolicyOverlay" class="absolute inset-0 bg-slate-950/80 backdrop-blur-md">
    </div>

    {{-- Modal Wrapper --}}
    <div class="relative flex min-h-full items-center justify-center p-3 sm:p-5">

        {{-- Modal Content --}}
        <div id="privacyPolicyContent" tabindex="-1"
            class="relative flex w-full max-w-5xl max-h-[92vh] flex-col overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-2xl scale-95 translate-y-5 transition-all duration-300">

            {{-- Header --}}
            <div
                class="relative shrink-0 overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950 px-5 py-8 sm:px-10 sm:py-10">

                {{-- Background Decoration --}}
                <div class="absolute -top-24 -right-20 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>

                <div class="relative z-10">

                    {{-- Close Button --}}
                    <button type="button" id="closePrivacyPolicy" aria-label="Close Privacy Policy"
                        class="absolute -top-3 -right-2 sm:top-0 sm:right-0 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 hover:rotate-90 transition-all duration-300">

                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>

                    {{-- Brand --}}
                    <div class="flex items-center gap-3 mb-6">

                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 shadow-lg shadow-cyan-500/20">
                            <i class="fa-solid fa-shield-halved text-2xl text-white"></i>
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

                    {{-- Title --}}
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-cyan-400/30 bg-cyan-400/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-cyan-300">
                        <i class="fa-solid fa-lock"></i>
                        Your Privacy Matters
                    </span>

                    <h2 id="privacyPolicyTitle"
                        class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-white">

                        Privacy
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-400">
                            Policy
                        </span>
                    </h2>

                    <p class="mt-4 max-w-2xl text-sm sm:text-base leading-7 text-slate-300">
                        Your trust is important to us. Learn how BIMS collects,
                        uses, protects, and manages your information.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-slate-300">

                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2">
                            <i class="fa-solid fa-shield-halved text-cyan-400"></i>
                            Data Protection
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2">
                            <i class="fa-solid fa-user-shield text-cyan-400"></i>
                            Privacy & Security
                        </span>

                    </div>

                </div>
            </div>


            {{-- Modal Body --}}
            <div class="flex-1 overflow-y-auto overscroll-contain bg-slate-50">

                <div class="px-5 py-8 sm:px-10 sm:py-10">

                    {{-- Introduction --}}
                    <section class="mb-10">

                        <div
                            class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-blue-50 p-5 sm:p-7">

                            <div class="flex items-center gap-3 mb-4">

                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-cyan-700 shadow-sm">
                                    <i class="fa-solid fa-file-shield text-xl"></i>
                                </div>

                                <h3 class="text-xl font-extrabold text-slate-900">
                                    Introduction
                                </h3>

                            </div>

                            <p class="text-sm sm:text-base leading-8 text-slate-600">
                                BIMS (Business Integration Management System),
                                developed by COMITS, respects your privacy and
                                is committed to protecting the personal and
                                business information entrusted to us.
                            </p>

                            <p class="mt-3 text-sm sm:text-base leading-8 text-slate-600">
                                This Privacy Policy explains how information
                                may be collected, used, stored, and protected
                                when you access or use our website and services.
                            </p>

                        </div>

                    </section>


                    {{-- Policy Sections --}}
                    <div class="space-y-8">

                        {{-- 01 Information Collection --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                    <i class="fa-solid fa-database text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        01. Information We Collect
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        Depending on how you use BIMS, we may
                                        collect the following categories of information:
                                    </p>

                                    <ul class="mt-4 space-y-3">

                                        <li class="flex gap-3 text-sm text-slate-600">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                <strong class="text-slate-800">Personal Information:</strong>
                                                Name, email address, phone number,
                                                and other details you provide.
                                            </span>
                                        </li>

                                        <li class="flex gap-3 text-sm text-slate-600">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                <strong class="text-slate-800">Business Information:</strong>
                                                Company details, branch information,
                                                customer records, supplier information,
                                                product details, and business transactions
                                                entered into the platform.
                                            </span>
                                        </li>

                                        <li class="flex gap-3 text-sm text-slate-600">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                <strong class="text-slate-800">Technical Information:</strong>
                                                IP address, browser type, device
                                                information, and usage information,
                                                where collected.
                                            </span>
                                        </li>

                                        <li class="flex gap-3 text-sm text-slate-600">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                <strong class="text-slate-800">Communication Information:</strong>
                                                Messages, inquiries, and support
                                                requests you send to us.
                                            </span>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 02 How We Use Information --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-gears text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        02. How We Use Your Information
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        Information may be used for the following
                                        legitimate business and service purposes:
                                    </p>

                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>To create and manage user accounts.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>To provide and maintain BIMS services.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>To process subscriptions and payments through applicable payment
                                                providers.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>To provide customer support and respond to inquiries.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>To improve functionality, performance, and user experience.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-emerald-600"></i>
                                            <span>To protect the platform and prevent unauthorized access or
                                                misuse.</span>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 03 Data Security --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                                    <i class="fa-solid fa-shield-halved text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        03. Data Protection & Security
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We take reasonable technical and organizational
                                        measures to protect information against
                                        unauthorized access, alteration, disclosure,
                                        or destruction.
                                    </p>

                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">

                                        <div class="rounded-xl bg-white border border-slate-200 p-4">
                                            <i class="fa-solid fa-user-lock text-xl text-purple-600"></i>
                                            <h4 class="mt-2 text-sm font-bold text-slate-800">
                                                Access Control
                                            </h4>
                                            <p class="mt-1 text-xs leading-6 text-slate-500">
                                                Access permissions based on user
                                                roles and responsibilities.
                                            </p>
                                        </div>

                                        <div class="rounded-xl bg-white border border-slate-200 p-4">
                                            <i class="fa-solid fa-server text-xl text-purple-600"></i>
                                            <h4 class="mt-2 text-sm font-bold text-slate-800">
                                                System Protection
                                            </h4>
                                            <p class="mt-1 text-xs leading-6 text-slate-500">
                                                Appropriate safeguards for
                                                platform infrastructure.
                                            </p>
                                        </div>

                                    </div>

                                    <p class="mt-4 text-xs leading-6 text-slate-500">
                                        No method of electronic storage or internet
                                        transmission is completely secure. Therefore,
                                        absolute security cannot be guaranteed.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 04 Cookies --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                                    <i class="fa-solid fa-cookie-bite text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        04. Cookies & Similar Technologies
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        BIMS may use cookies or similar technologies
                                        to support essential website functionality,
                                        maintain sessions, remember preferences,
                                        and understand website usage where applicable.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        You can manage cookies through your browser
                                        settings. Disabling certain cookies may affect
                                        some website functionality.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 05 Information Sharing --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                    <i class="fa-solid fa-share-nodes text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        05. Information Sharing
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We do not sell personal information as
                                        part of our ordinary business operations.
                                        Information may be shared only when necessary,
                                        such as:
                                    </p>

                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                With service providers who support
                                                hosting, payment processing, or
                                                technical operations.
                                            </span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                When required by applicable law,
                                                regulation, or lawful government request.
                                            </span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-cyan-600"></i>
                                            <span>
                                                To protect the rights, safety, security,
                                                and integrity of BIMS, its users,
                                                or other parties.
                                            </span>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 06 Data Retention --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        06. Data Retention
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We retain information for as long as necessary
                                        to provide our services, maintain business
                                        records, fulfill contractual obligations,
                                        resolve disputes, and comply with applicable
                                        legal requirements.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        When information is no longer required,
                                        we may securely delete or anonymize it,
                                        subject to applicable retention obligations
                                        and backup processes.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 07 User Rights --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-pink-100 text-pink-700">
                                    <i class="fa-solid fa-user-check text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        07. Your Privacy Rights
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        Subject to applicable laws, you may have
                                        the right to:
                                    </p>

                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-pink-600"></i>
                                            <span>Request access to your personal information.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-pink-600"></i>
                                            <span>Request correction of inaccurate information.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-pink-600"></i>
                                            <span>Request deletion of eligible personal information.</span>
                                        </li>

                                        <li class="flex gap-3">
                                            <i class="fa-solid fa-check-circle mt-1 text-pink-600"></i>
                                            <span>Withdraw consent where processing is based on consent.</span>
                                        </li>

                                    </ul>

                                    <p class="mt-3 text-xs leading-6 text-slate-500">
                                        Certain records may need to be retained
                                        where required by law or legitimate
                                        business obligations.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 08 Third Party Services --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                    <i class="fa-solid fa-link text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        08. Third-Party Services
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        BIMS may integrate with third-party services
                                        such as payment gateways, hosting providers,
                                        email services, and other external platforms.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        These services may have their own privacy
                                        policies and data handling practices.
                                        We encourage users to review the applicable
                                        policies of those providers.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 09 Children's Privacy --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                                    <i class="fa-solid fa-children text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        09. Children's Privacy
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        BIMS is a business management platform
                                        and is not intended to be directed toward
                                        children. We do not knowingly seek to
                                        collect children's personal information
                                        through our services.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <div class="border-t border-slate-200"></div>


                        {{-- 10 Policy Updates --}}
                        <section>

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                    <i class="fa-solid fa-arrows-rotate text-lg"></i>
                                </div>

                                <div class="flex-1">

                                    <h3 class="text-lg font-bold text-slate-900">
                                        10. Changes to This Privacy Policy
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        We may update this Privacy Policy from
                                        time to time to reflect changes in our
                                        services, business practices, or applicable
                                        legal requirements.
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-600">
                                        Any updated version will be published
                                        on our website with the revised
                                        effective date.
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
                                    <i class="fa-solid fa-envelope-open-text text-xl"></i>
                                </div>

                                <h3 class="mt-5 text-2xl font-extrabold text-white">
                                    Contact Us
                                </h3>

                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    If you have questions, concerns, or requests
                                    regarding this Privacy Policy or your personal
                                    information, please contact our team.
                                </p>

                                <div class="mt-6 space-y-3">

                                    {{-- Update email if necessary --}}
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


            {{-- Footer --}}
            <div class="shrink-0 border-t border-slate-200 bg-white px-5 py-4 sm:px-8">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <p class="text-xs text-slate-500 text-center sm:text-left">
                        © {{ date('Y') }} BIMS. Developed by
                        <span class="font-bold text-slate-800">COMITS</span>.
                    </p>

                    <button type="button" id="closePrivacyPolicyBottom"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition-all duration-300 hover:bg-cyan-600">

                        <i class="fa-solid fa-check"></i>
                        Got It
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>
{{-- ================= END PRIVACY POLICY MODAL ================= --}}
