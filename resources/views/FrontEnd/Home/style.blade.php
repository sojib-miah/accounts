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
        color: #64748b;
        background: transparent;
        transition: all .3s ease;
    }

    .billing-btn.active {
        color: #fff;
        background: #06b6d4;
        box-shadow: 0 10px 25px rgba(37, 99, 235, .25);
    }

    .pricing-card {
        @apply relative flex flex-col h-full bg-white rounded-xl border border-slate-300 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg;
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
        @apply border-t-4 border-t-cyan-500;
    }

    .header-standard {
        @apply border-t-4 border-t-cyan-500 bg-cyan-50/50;
    }

    .header-enterprise {
        @apply border-t-4 border-t-indigo-600;
    }

    .hero-section {
        @apply relative overflow-hidden bg-gradient-to-br from-cyan-50 pt-32 via-white to-cyan-50;
    }

    .hero-container {
        @apply relative max-w-7xl mx-auto px-6 lg:px-8 pt-10 pb-20 lg:pt-14 lg:pb-24 grid lg:grid-cols-2 gap-16 items-center;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }


    .hero-blur-left {
        @apply absolute -top-40 -left-32 w-96 h-96 rounded-full bg-cyan-300 opacity-20 blur-3xl;
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
        @apply text-cyan-500 text-base;
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
        @apply bg-cyan-500 text-white border-cyan-500 shadow-md -translate-y-0.5;
    }

    .plan-btn:active {
        @apply translate-y-0 shadow-none;
    }

    /* Highlight standard plan button */
    .pricing-card:has(.ribbon) .plan-btn {
        @apply bg-cyan-500 text-white hover:bg-cyan-700;
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
        @apply relative py-20 overflow-hidden bg-gradient-to-br from-cyan-100 via-white to-cyan-100;
    }

    .card-body {
        @apply p-6 flex flex-col flex-1 justify-between gap-6 bg-white;
    }

    .partner-container {
        @apply relative max-w-7xl mx-auto px-6 lg:px-8;
    }

    .logo-card {
        @apply w-34 h-34 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm cursor-pointer transition-all duration-500;
    }

    .logo-card:hover {
        @apply border-cyan-500 shadow-2xl;
        transform: translateY(-6px) scale(0.96);
    }

    .logo-image {
        @apply w-24 h-24 object-contain transition-all duration-500;
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
        @apply mt-16 py-10 border-y border-cyan-200;
    }

    .payment-banner {
        @apply w-full h-auto;
    }

    #backToTop {
        @apply fixed bottom-6 right-6 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-cyan-500 text-white shadow-lg shadow-cyan-500/30 transition-all duration-300 opacity-0 invisible pointer-events-none translate-y-4 hover:bg-cyan-600 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-cyan-200;
    }

    #backToTop.show {
        @apply opacity-100 visible pointer-events-auto translate-y-0;
    }

    button {
        cursor: pointer;
    }

    .footer {
        background: #fafbfc;
        border-top: 1px solid #e5e7eb;
        padding: 80px 0 0;
    }
</style>
