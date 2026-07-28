<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create An Account</title>
        <link rel="icon" type="image/x-icon"
            href="{{ optional(setting())->favaicon ? asset('uploads/settings/' . setting()->favaicon) : asset('default-favicon.ico') }}" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>

    <body>
        <main class="relative min-h-screen flex items-center justify-center bg-cover bg-center"
            style="background-image:url('{{ asset('assets/img/bg.jpg') }}')">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <!-- Container -->
            <div
                class="relative z-10 w-full max-w-6xl mx-5 overflow-hidden rounded-3xl shadow-2xl bg-white/10 backdrop-blur-xl">
                <div class="grid lg:grid-cols-2">
                    <!-- ================= LEFT ================= -->
                    <div class="hidden lg:flex flex-col justify-center p-12 text-white">
                        <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : '' }}"
                            class="w-52 mb-10 bg-white rounded-xl p-3">
                        <h1 class="text-5xl font-bold leading-tight">
                            Create <br> Your Account
                        </h1>
                        <p class="mt-6 text-white/80 text-lg leading-8">
                            Join our secure digital banking platform and enjoy fast,
                            safe and convenient financial services.
                        </p>
                        <div class="mt-12 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center">
                                    <i class="fa-solid fa-user-shield"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">
                                        Secure Registration
                                    </h4>
                                    <p class="text-sm text-white/70">
                                        Your information is protected.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">
                                        Fast Setup
                                    </h4>
                                    <p class="text-sm text-white/70">
                                        Register within one minute.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">
                                        Safe Banking
                                    </h4>
                                    <p class="text-sm text-white/70">
                                        Modern security for your account.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ================= RIGHT ================= -->
                    <div class="p-8 lg:p-12 bg-white/10 backdrop-blur-2xl border-l border-white/20 text-white">
                        <h2 class="text-3xl font-bold">
                            Registration
                        </h2>
                        <p class="mt-2 text-white/70">
                            Fill in your details to create an account.
                        </p>
                        <form id="formAuthentication" class="mb-6" method="POST"
                            action="{{ route('admin.register.store') }}">
                            @csrf
                            <!-- Username -->
                            <div class="mt-6">
                                <label class="font-medium">
                                    Username
                                </label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}"
                                    placeholder="Enter your username"
                                    class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-gray-300 backdrop-blur-md outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-400/40">
                            </div>
                            @error('name')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                            <!-- Email -->
                            <div class="mt-5">
                                <label class="font-medium">
                                    Email Address
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-gray-300 backdrop-blur-md outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-400/40">
                            </div>
                            @error('email')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                            <!-- Password -->
                            <div class="mt-5">
                                <label class="font-medium">
                                    Password
                                </label>
                                <div
                                    class="mt-2 flex items-center rounded-xl border border-white/20 bg-white/10 backdrop-blur-md">
                                    <input id="password" type="password" name="password" placeholder="Enter Password"
                                        class="flex-1 bg-transparent px-4 py-3 text-white placeholder:text-gray-300 outline-none">
                                    <button id="togglePassword" type="button"
                                        class="px-4 text-white hover:text-red-300">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Confirm Password -->
                            <div class="mt-5">
                                <label class="font-medium">
                                    Confirm Password
                                </label>
                                <div
                                    class="mt-2 flex items-center rounded-xl border border-white/20 bg-white/10 backdrop-blur-md">
                                    <input id="confirmPassword" name="password_confirmation" type="password"
                                        placeholder="Confirm Password"
                                        class="flex-1 bg-transparent px-4 py-3 text-white placeholder:text-gray-300 outline-none">
                                    <button id="toggleConfirmPassword" type="button"
                                        class="px-4 text-white hover:text-red-300">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Terms -->
                            <div class="mt-6 flex items-center gap-3">
                                <input type="checkbox" name="terms" class="h-4 w-4 rounded accent-red-500">
                                <label class="text-sm text-white/80">
                                    I agree to the
                                    <a href="javascript:void(0);" class="font-semibold text-red-300 hover:text-red-200">
                                        Terms & Conditions
                                    </a>
                                </label>
                            </div>
                            <!-- Register Button -->
                            <button type="submit" id="registerBtn"
                                class="mt-8 w-full rounded-xl bg-gradient-to-r from-red-600 via-red-500 to-red-700 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-red-500/40">
                                Create Account
                            </button>
                        </form>
                        <!-- Login -->
                        <p class="mt-6 text-center text-white/80">
                            Already have an account?
                            <a href="{{ route('admin.login') }}"
                                class="font-semibold text-red-300 transition hover:text-red-200">
                                Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </body>
    <script>
        function setupPasswordToggle(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const icon = button.querySelector('i');

            button.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                // toggle icon
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        // initialize both toggles
        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('confirmPassword', 'toggleConfirmPassword');
    </script>


</html>
