<script>
    // Navbar Scroll Effect
    const navbar = document.getElementById("navbar");
    const topBar = document.getElementById("topBar");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
            if (topBar) topBar.classList.add("hidden");
            navbar.classList.remove("bg-transparent");
            navbar.classList.add(
                "bg-white/40",
                "backdrop-blur-2xl",
                "shadow-lg",
            );

        } else {
            if (topBar) topBar.classList.remove("hidden");
            navbar.classList.remove(
                "bg-white/40",
                "backdrop-blur-2xl",
                "shadow-lg",
            );
            navbar.classList.add("bg-transparent");
        }
    });

    // Mobile Menu Toggle
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener("click", () => {
            const isHidden = mobileMenu.classList.toggle("hidden");
            menuBtn.innerHTML = isHidden ?
                '<i class="fa-solid fa-bars"></i>' :
                '<i class="fa-solid fa-xmark"></i>';
            menuBtn.setAttribute("aria-expanded", String(!isHidden));
        });
        // Close menu when clicking a menu link
        mobileMenu.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", () => {
                mobileMenu.classList.add("hidden");
                menuBtn.innerHTML =
                    '<i class="fa-solid fa-bars"></i>';
                menuBtn.setAttribute("aria-expanded", "false");
            });
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

    function switchLanguage(locale) {
        const routes = {
            en: @json(route('language.switch', 'en')),
            bn: @json(route('language.switch', 'bn'))
        };
        if (routes[locale]) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = routes[locale];
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = @json(csrf_token());
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const backToTop = document.getElementById('backToTop');
        if (!backToTop) return;
        // Show / Hide button on scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }, {
            passive: true
        });
        // Scroll smoothly to top
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('implementationModal');
        const content = document.getElementById('implementationContent');
        const openBtn = document.getElementById('implementationBtn');
        const closeBtn = document.getElementById('closeImplementation');
        const closeBottom = document.getElementById('closeImplementationBottom');
        const overlay = document.getElementById('implementationOverlay');
        if (!modal || !content || !openBtn) return;

        function openModal() {
            modal.classList.remove('invisible', 'opacity-0');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
            closeBtn?.focus();
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            document.body.style.overflow = '';
            openBtn.focus();
        }
        openBtn.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        overlay?.addEventListener('click', closeModal);
        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('invisible')) {
                closeModal();
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('dataMigrationModal');
        const content = document.getElementById('dataMigrationContent');
        const openBtn = document.getElementById('dataMigrationBtn');
        const closeBtn = document.getElementById('closeDataMigration');
        const closeBottom = document.getElementById('closeDataMigrationBottom');
        const overlay = document.getElementById('dataMigrationOverlay');
        if (!modal || !content || !openBtn) return;

        function openModal() {
            modal.classList.remove('invisible', 'opacity-0');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
            closeBtn?.focus();
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            document.body.style.overflow = '';
            openBtn.focus();
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close on overlay click
        overlay?.addEventListener('click', closeModal);
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (
                e.key === 'Escape' &&
                !modal.classList.contains('invisible')
            ) {
                closeModal();
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('userTrainingModal');
        const content = document.getElementById('userTrainingContent');
        const openBtn = document.getElementById('userTrainingBtn');
        const closeBtn = document.getElementById('closeUserTraining');
        const closeBottom = document.getElementById('closeUserTrainingBottom');
        const overlay = document.getElementById('userTrainingOverlay');
        if (!modal || !content || !openBtn) return;

        function openModal() {
            modal.classList.remove('invisible', 'opacity-0');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
            closeBtn?.focus();
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            document.body.style.overflow = '';
            openBtn.focus();
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close when clicking outside
        overlay?.addEventListener('click', closeModal);
        // Close with Escape
        document.addEventListener('keydown', function(e) {
            if (
                e.key === 'Escape' &&
                !modal.classList.contains('invisible')
            ) {
                closeModal();
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('technicalSupportModal');
        const content = document.getElementById('technicalSupportContent');
        const openBtn = document.getElementById('technicalSupportBtn');
        const closeBtn = document.getElementById('closeTechnicalSupport');
        const closeBottom = document.getElementById('closeTechnicalSupportBottom');
        const overlay = document.getElementById('technicalSupportOverlay');
        if (!modal || !content || !openBtn) return;

        function openModal() {
            modal.classList.remove('invisible', 'opacity-0');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
            closeBtn?.focus();
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            document.body.style.overflow = '';
            openBtn.focus();
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close on overlay click
        overlay?.addEventListener('click', closeModal);
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (
                e.key === 'Escape' &&
                !modal.classList.contains('invisible')
            ) {
                closeModal();
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('customizationModal');
        const content = document.getElementById('customizationContent');
        const openBtn = document.getElementById('customizationBtn');
        const closeBtn = document.getElementById('closeCustomization');
        const closeBottom = document.getElementById('closeCustomizationBottom');
        const overlay = document.getElementById('customizationOverlay');
        if (!modal || !content || !openBtn) return;

        function openModal() {
            modal.classList.remove('invisible', 'opacity-0');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
            closeBtn?.focus();
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            document.body.style.overflow = '';
            openBtn.focus();
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close on overlay click
        overlay?.addEventListener('click', closeModal);
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (
                e.key === 'Escape' &&
                !modal.classList.contains('invisible')
            ) {
                closeModal();
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('maintenanceModal');
        const content = document.getElementById('maintenanceContent');
        const openBtn = document.getElementById('maintenanceBtn');
        const closeBtn = document.getElementById('closeMaintenance');
        const closeBottom = document.getElementById('closeMaintenanceBottom');
        const overlay = document.getElementById('maintenanceOverlay');
        if (!modal || !content || !openBtn) return;

        function openModal() {
            modal.classList.remove('invisible', 'opacity-0');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
            closeBtn?.focus();
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            document.body.style.overflow = '';
            openBtn.focus();
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close on overlay click
        overlay?.addEventListener('click', closeModal);
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (
                e.key === 'Escape' &&
                !modal.classList.contains('invisible')
            ) {
                closeModal();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('aboutUsModal');
        const content = document.getElementById('aboutUsContent');
        const openBtn = document.getElementById('aboutUsBtn');
        const closeBtn = document.getElementById('closeAboutUs');
        const closeBottom = document.getElementById('closeAboutUsBottom');
        const overlay = document.getElementById('aboutUsOverlay');
        if (!modal || !content || !openBtn) return;
        let previousFocus = null;
        const focusableSelector = [
            'button:not([disabled])',
            'a[href]',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            '[tabindex]:not([tabindex="-1"])'
        ].join(',');

        function openModal() {
            previousFocus = document.activeElement;
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                closeBtn?.focus();
            }, 100);
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            modal.classList.remove('visible', 'opacity-100');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            } else {
                openBtn.focus();
            }
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close when clicking overlay
        overlay?.addEventListener('click', closeModal);
        // Close using Escape key and trap keyboard focus
        document.addEventListener('keydown', function(e) {
            if (modal.classList.contains('invisible')) return;
            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }
            if (e.key === 'Tab') {
                const focusableElements = [
                    ...content.querySelectorAll(focusableSelector)
                ];
                if (!focusableElements.length) {
                    e.preventDefault();
                    content.focus();
                    return;
                }
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (
                    !e.shiftKey &&
                    document.activeElement === lastElement
                ) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('privacyPolicyModal');
        const content = document.getElementById('privacyPolicyContent');
        const openBtn = document.getElementById('privacyPolicyBtn');
        const closeBtn = document.getElementById('closePrivacyPolicy');
        const closeBottom = document.getElementById('closePrivacyPolicyBottom');
        const overlay = document.getElementById('privacyPolicyOverlay');
        if (!modal || !content || !openBtn) return;
        let previousFocus = null;
        const focusableSelector = [
            'button:not([disabled])',
            'a[href]',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            '[tabindex]:not([tabindex="-1"])'
        ].join(',');

        function openModal() {
            previousFocus = document.activeElement;
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                closeBtn?.focus();
            }, 100);
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            modal.classList.remove('visible', 'opacity-100');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            } else {
                openBtn.focus();
            }
        }
        // Open
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Overlay click
        overlay?.addEventListener('click', closeModal);
        // Escape and keyboard focus
        document.addEventListener('keydown', function(e) {
            if (modal.classList.contains('invisible')) return;
            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }
            if (e.key === 'Tab') {
                const focusable = [
                    ...content.querySelectorAll(focusableSelector)
                ];
                if (!focusable.length) {
                    e.preventDefault();
                    content.focus();
                    return;
                }
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('termsConditionsModal');
        const content = document.getElementById('termsConditionsContent');
        const openBtn = document.getElementById('termsConditionsBtn');
        const closeBtn = document.getElementById('closeTermsConditions');
        const closeBottom = document.getElementById('closeTermsConditionsBottom');
        const overlay = document.getElementById('termsConditionsOverlay');
        if (!modal || !content || !openBtn) return;
        let previousFocus = null;
        const focusableSelector = [
            'button:not([disabled])',
            'a[href]',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            '[tabindex]:not([tabindex="-1"])'
        ].join(',');

        function openModal() {
            previousFocus = document.activeElement;
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            content.classList.remove('scale-95', 'translate-y-5');
            content.classList.add('scale-100', 'translate-y-0');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                closeBtn?.focus();
            }, 100);
        }

        function closeModal() {
            modal.classList.add('invisible', 'opacity-0');
            modal.classList.remove('visible', 'opacity-100');
            content.classList.remove('scale-100', 'translate-y-0');
            content.classList.add('scale-95', 'translate-y-5');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            } else {
                openBtn.focus();
            }
        }
        // Open modal
        openBtn.addEventListener('click', openModal);
        // Close buttons
        closeBtn?.addEventListener('click', closeModal);
        closeBottom?.addEventListener('click', closeModal);
        // Close on overlay click
        overlay?.addEventListener('click', closeModal);
        // Escape key and focus management
        document.addEventListener('keydown', function(e) {
            if (modal.classList.contains('invisible')) return;
            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }
            if (e.key === 'Tab') {
                const focusable = [
                    ...content.querySelectorAll(focusableSelector)
                ];
                if (!focusable.length) {
                    e.preventDefault();
                    content.focus();
                    return;
                }
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });
    });
</script>
