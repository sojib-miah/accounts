<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password Pages</title>
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
                        <h1 class="text-3xl font-bold leading-tight">
                            Welcome to COMITS
                        </h1>
                        <p class="mt-6 text-white/80 text-lg leading-8">
                            Join our secure digital banking platform and enjoy fast,
                            safe and convenient financial services.
                        </p>
                        <div class="mt-12 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center">
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
                                <div class="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center">
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
                                <div class="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center">
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
                            Forget Password
                        </h2>
                        <p class="mt-2 text-white/70">
                            Fill the form to reset your password
                        </p>
                        <form id="formAuthentication" class="mb-6" method="POST"
                            action="{{ route('admin.password.email') }}">
                            @csrf
                            @if (session('error'))
                                <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div
                                    class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <!-- Email -->
                            <div class="mt-5">
                                <label class="font-medium">
                                    Email Address
                                </label>
                                <input id="email" type="email" name="email" placeholder="Enter your email"
                                    class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder:text-gray-300 backdrop-blur-md outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/40">
                            </div>
                            @error('email')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                            <div class="mt-8 flex gap-5">
                                <a href="{{ route('admin.login') }}" id="backBtn"
                                    class="w-1/2 rounded-xl text-center border border-cyan-600 bg-white py-4 font-semibold text-cyan-600 transition hover:bg-cyan-50">
                                    Back To Login
                                </a>
                                <button id="registerBtn" type="submit"
                                    class="w-1/2 rounded-xl bg-gradient-to-r from-cyan-600 via-cyan-500 to-cyan-700 py-4 font-semibold text-white transition">
                                    Submit
                                </button>
                            </div>
                        </form>
                        <!-- Login -->
                        <p class="mt-6 text-center text-white/80">
                            Already have an account?
                            <a href="{{ route('admin.login') }}"
                                class="font-semibold text-cyan-300 transition hover:text-cyan-200">
                                Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </body>

</html>
