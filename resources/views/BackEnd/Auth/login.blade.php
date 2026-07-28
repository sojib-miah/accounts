<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>

        <link rel="icon" type="image/x-icon"
            href="{{ optional(setting())->favaicon ? asset('uploads/settings/' . setting()->favaicon) : asset('default-favicon.ico') }}" />
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>

    <body>
        <main class="relative flex min-h-screen items-center justify-center bg-cover bg-center"
            style="background-image: url('{{ asset('assets/img/bg.jpg') }}');">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full max-w-md px-5">
                <div class="rounded-3xl border border-white/30 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
                    <div class="mb-8 flex justify-center">
                        <div class="rounded-2xl bg-white px-3 py-2 shadow-lg">
                            <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : '' }}"
                                alt="COMITS" class="h-auto w-44 object-contain">
                        </div>
                    </div>
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-white">
                            Welcome Back
                        </h2>
                        <p class="mt-2 text-white/70">
                            Sign in to access your account
                        </p>
                    </div>
                    <form id="formAuthentication" class="mb-6" method="POST"
                        action="{{ route('admin.login.submit') }}">

                        @if (session('error'))
                            <div class="my-2 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="my-2 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @csrf
                        <div class="mt-8">
                            <label class="text-sm text-white">
                                Email or Username
                            </label>
                            <div
                                class="mt-2 flex items-center rounded-xl border border-white/20 bg-white/10 backdrop-blur-md">
                                <div class="flex h-12 w-12 items-center justify-center text-white">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <input id="mobile" type="text" name="login" value="{{ old('login') }}"
                                    placeholder="Enter your email or username"
                                    class="flex-1 bg-transparent text-white placeholder:text-gray-300 outline-none">
                            </div>
                            @error('login')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-5">
                            <label class="text-sm text-white">
                                Password
                            </label>
                            <div
                                class="mt-2 flex items-center rounded-xl border border-white/20 bg-white/10 backdrop-blur-md">
                                <div class="flex h-12 w-12 items-center justify-center text-white">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input id="password" type="password" placeholder="Password" name="password"
                                    class="flex-1 bg-transparent text-white placeholder:text-gray-300 outline-none">
                                <button type="button" id="togglePassword"
                                    class="flex h-12 w-12 items-center justify-center text-white">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                            <label class="flex items-center gap-2 text-sm text-white">
                                <input type="checkbox" name="remember">
                                Remember me
                            </label>
                            <a href="{{ route('admin.password.request') }}"
                                class="text-cyan-300 transition hover:text-cyan-200">
                                Forgot Password?
                            </a>
                        </div>
                        <!-- Button -->
                        <button type="submit" id="loginBtn"
                            class="mt-8 w-full rounded-xl bg-red-500 py-3 text-lg font-semibold text-white transition duration-300 hover:scale-[1.02]">
                            Login
                        </button>
                    </form>
                    <p class="mt-6 text-center text-white/80">
                        Don't have an account?
                        <a href="{{ route('admin.register') }}"
                            class="font-semibold text-cyan-300 hover:text-cyan-200">
                            Register
                        </a>
                    </p>
                </div>
            </div>
        </main>
    </body>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            // toggle type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // toggle icon
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>

</html>
