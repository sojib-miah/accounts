<!doctype html>

<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-bs-theme="light" data-assets-path="../../assets/" data-template="vertical-menu-template">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="robots" content="noindex, nofollow" />
        <title>@yield('title')</title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon"
            href="{{ optional(setting())->favaicon ? asset('uploads/settings/' . setting()->favaicon) : asset('default-favicon.ico') }}" />


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
            rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/iconify-icons.css') }}" />

        <script src="{{ asset('/assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

        <!-- Core CSS -->
        <!-- build:css assets/vendor/css/theme.css  -->

        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/node-waves/node-waves.css') }}" />

        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/pickr/pickr-themes.css') }}" />

        <link rel="stylesheet" href="{{ asset('/assets/vendor/css/core.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/css/demo.css') }}" />

        <!-- Vendors CSS -->

        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

        <!-- endbuild -->

        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/swiper/swiper.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet"
            href="{{ asset('/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/flag-icons.css') }}" />

        <!-- Page CSS -->
        <link rel="stylesheet" href="{{ asset('/assets/vendor/css/pages/cards-advance.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/fontawesome.css') }}" />

        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/tagify/tagify.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/typeahead-js/typeahead.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('/assets/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="{{ asset('/assets/vendor/js/template-customizer.js') }}"></script>

        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

        <script src="{{ asset('/assets/js/config.js') }}"></script>

        @stack('styles')

        <style>
            .select2-container--default .select2-results__option--selected {
                background-color: #393C64 !important;
                color: #ddd !important;
            }

            .custome .select2-container--default {
                width: 180px !important;
            }
        </style>
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar  ">
            <div class="layout-container">
                <!-- Menu -->

                @include('BackEnd.Layouts.sidebar')

                <div class="menu-mobile-toggler d-xl-none rounded-1">
                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                        <i class="ti tabler-menu icon-base"></i>
                        <i class="ti tabler-chevron-right icon-base"></i>
                    </a>
                </div>
                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->

                    @include('BackEnd.Layouts.navbar')

                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        @yield('content')
                        <!-- / Content -->

                        <!-- Footer -->
                        @include('BackEnd.Layouts.footer')
                        <!-- / Footer -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/theme.js  -->
        <script src="{{ asset('/assets/vendor/libs/jquery/jquery.js') }}"></script>

        <script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('/assets/vendor/libs/node-waves/node-waves.js') }}"></script>

        <script src="{{ asset('/assets/vendor/libs/pickr/pickr.js') }}"></script>

        <script src="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

        <script src="{{ asset('/assets/vendor/libs/hammer/hammer.js') }}"></script>

        <script src="{{ asset('/assets/vendor/libs/i18n/i18n.js') }}"></script>

        <script src="{{ asset('/assets/vendor/js/menu.js') }}"></script>

        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="{{ asset('/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
        <script src="{{ asset('/assets/vendor/libs/swiper/swiper.js') }}"></script>
        <script src="{{ asset('/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

        <!-- Main JS -->

        <script src="{{ asset('/assets/js/main.js') }}"></script>

        <!-- Page JS -->
        <script src="{{ asset('/assets/js/dashboards-analytics.js') }}"></script>

        <script src="{{ asset('/assets/js/app-access-permission.js') }}"></script>
        <script src="{{ asset('/assets/js/modal-add-permission.js') }}"></script>
        <script src="{{ asset('/assets/js/modal-edit-permission.js') }}"></script>

        <script src="{{ asset('/assets/js/app-access-roles.js') }}"></script>
        <script src="{{ asset('/assets/js/modal-add-role.js') }}"></script>
        <script src="{{ asset('/assets/js/app-user-list.js') }}"></script>
        <script src="{{ asset('/assets/js/extended-ui-sweetalert2.js') }}"></script>
        <script src="{{ asset('/assets/js/app-ecommerce-product-add.js') }}"></script>
        <script src="{{ asset('/assets/js/forms-selects.js') }}"></script>
        <script src="{{ asset('/assets/js/forms-tagify.js') }}"></script>
        <script src="{{ asset('/assets/js/forms-typeahead.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            </script>
        @endif

        @yield('script')
        @stack('scripts')
    </body>

</html>
