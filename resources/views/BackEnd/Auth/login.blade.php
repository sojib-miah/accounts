<!doctype html>

<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="robots" content="noindex, nofollow" />
        <title>Login Cover - Pages</title>

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

        <!-- Vendor -->
        <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/@form-validation/form-validation.css') }}" />

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('/assets/vendor/css/pages/page-auth.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('/assets/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="{{ asset('/assets/vendor/js/template-customizer.js') }}"></script>

        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

        <script src="{{ asset('/assets/js/config.js') }}"></script>
    </head>

    <body>
        <!-- Content -->
        <div class="authentication-wrapper authentication-cover" style="position: relative;">
            <!-- Logo -->
            <a href="{{ route('dashboard.index') }}" class="app-brand auth-cover-brand">
                <span class="app-brand-text demo text-heading fw-bold">ComitsBD</span>
            </a>
            <!-- /Logo -->
            <div class="authentication-inner row m-0">
                <!-- /Left Text -->
                <div class="d-none d-xl-flex p-0">
                    {{-- <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                        <img src="{{ asset('/assets/img/illustrations/auth-login-illustration-light.png') }}"
                            alt="auth-login-cover" class="my-5 auth-illustration"
                            data-app-light-img="illustrations/auth-login-illustration-light.png"
                            data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
                        <img src="{{ asset('/assets/img/illustrations/bg-shape-image-light.png') }}"
                            alt="auth-login-cover" class="platform-bg"
                            data-app-light-img="illustrations/bg-shape-image-light.png"
                            data-app-dark-img="illustrations/bg-shape-image-dark.png" />
                    </div> --}}
                </div>
                <!-- /Left Text -->

                <!-- Login -->
                <div class="d-flex col-12 col-xl-4 align-items-center justify-content-center authentication-bg p-sm-12 p-6"
                    style="position: absolute; top: 0; height:100%; left: 50%; transform: translateX(-50%);">

                    <div class="w-px-400 mx-auto mt-12 pt-5">
                        <h4 class="mb-1">Welcome to ComitsBD! 👋</h4>
                        <p class="mb-6">Please sign-in to your account and start the adventure</p>

                        <form id="formAuthentication" class="mb-6" method="POST"
                            action="{{ route('admin.login.submit') }}">

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @csrf
                            <div class="mb-6 form-control-validation">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="email" name="login"
                                    placeholder="Enter your email or username" autofocus value="{{ old('login') }}" />
                                @error('login')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-6 form-password-toggle form-control-validation">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base ti tabler-eye-off"></i></span>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="my-8">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0 ms-2">
                                        <input class="form-check-input" type="checkbox" id="remember-me"
                                            name="remember" />
                                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                                    </div>
                                    <a href="{{ route('admin.password.request') }}">
                                        <p class="mb-0">Forgot Password?</p>
                                    </a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                        </form>

                        <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="{{ route('admin.register') }}">
                                <span>Create an account</span>
                            </a>
                        </p>

                        <div class="divider my-6">
                            <div class="divider-text">or</div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-facebook me-1_5">
                                <i class="icon-base ti tabler-brand-facebook-filled icon-20px"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-twitter me-1_5">
                                <i class="icon-base ti tabler-brand-twitter-filled icon-20px"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-github me-1_5">
                                <i class="icon-base ti tabler-brand-github-filled icon-20px"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-google-plus">
                                <i class="icon-base ti tabler-brand-google-filled icon-20px"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Login -->
            </div>
        </div>

        <!-- / Content -->

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
        <script src="{{ asset('/assets/vendor/libs/@form-validation/popular.js') }}"></script>
        <script src="{{ asset('/assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
        <script src="{{ asset('/assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>

        <!-- Main JS -->

        <script src="{{ asset('/assets/js/main.js') }}"></script>

        <!-- Page JS -->
        <script src="{{ asset('/assets/js/pages-auth.js') }}"></script>
    </body>

</html>
