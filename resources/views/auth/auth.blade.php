@php
    $mode = $mode ?? 'login';
@endphp

<x-guest-layout :mode="$mode">

    {{-- LOGIN FORM --}}
    <div id="loginForm"
         class="auth-form {{ $mode === 'login' ? 'auth-form-active' : '' }}">

        {{-- STATUS MESSAGE --}}
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-base text-emerald-700 
                        flex items-center gap-2 animate-soft-pop">
                <i class="fas fa-check-circle text-lg"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- HEADER --}}
        <div class="text-center mb-12">
            <h2 class="text-5xl font-semibold text-gray-900 animate-slide-up">Welcome Back</h2>
            <p class="text-gray-500 text-base mt-2 animate-fade-in delay-100">
                Log in to continue your class activities.
            </p>
        </div>

        {{-- LOGIN FORM BODY --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-9">
            @csrf

            {{-- EMAIL --}}
            <div class="animate-slide-up delay-150">
                <label class="text-[17px] font-semibold text-gray-700 mb-3 block">Email Address</label>

                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-yellow-500 text-lg"></i>

                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full pl-14 pr-5 py-4.5 text-lg rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 
                                  hover:border-yellow-400 transition-all duration-200"
                           placeholder="you@example.com" required>
                </div>

                @error('email')
                    <p class="text-sm text-red-500 mt-1 flex items-center gap-1 animate-fade-in">
                        <i class="fas fa-circle-exclamation text-[13px]"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="animate-slide-up delay-200">
                <label class="text-[17px] font-semibold text-gray-700 mb-3 block">Password</label>

                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-yellow-500 text-lg"></i>

                    <input type="password" name="password" id="login_password"
                           class="w-full pl-14 pr-14 py-4.5 text-lg rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500
                                  hover:border-yellow-400 transition-all duration-200"
                           placeholder="Your password" required>

                    <button type="button" onclick="toggleLoginPassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-600 text-lg">
                        <i class="fas fa-eye" id="loginToggleIcon"></i>
                    </button>
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex justify-between items-center mt-4 animate-fade-in delay-300">
                    <label class="flex items-center gap-2 text-[15px] text-gray-700">
                        <input type="checkbox" name="remember"
                            class="w-5 h-5 text-yellow-500 rounded border-gray-300 focus:ring-yellow-400">
                        Remember me
                    </label>

                    {{-- NOW: toggle, no reload --}}
                    <a href="#"
                       data-auth-toggle="to-forgot"
                       class="text-[15px] font-semibold text-blue-600 hover:text-yellow-700 hover:underline">
                        Forgot password?
                    </a>
                </div>

                @error('password')
                    <p class="text-sm text-red-500 mt-1 flex items-center gap-1 animate-fade-in">
                        <i class="fas fa-circle-exclamation text-[13px]"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- LOGIN BUTTON --}}
            <button type="submit"
                    class="w-full py-5 bg-gradient-to-r from-yellow-500 to-yellow-400 text-white font-semibold 
                           rounded-2xl shadow-lg hover:shadow-xl hover:scale-[1.03]
                           transition-all duration-300 animate-soft-pop delay-300 text-xl">
                <i class="fas fa-arrow-right-to-bracket mr-2"></i>
                Sign In
            </button>

            {{-- SWITCH TO REGISTER --}}
            <p class="text-center text-[15px] text-gray-600 mt-7 animate-fade-in delay-400">
                Don’t have an account?
                <a href="{{ route('register') }}"
                   data-auth-toggle="to-register"
                   class="font-semibold text-blue-600 hover:text-yellow-700 hover:underline">
                    Sign up
                </a>
            </p>
        </form>

        {{-- FOOTER --}}
        <div class="flex items-center justify-center gap-8 text-[14px] text-gray-500 mt-10 pt-7 
                    border-t border-gray-200 animate-fade-in delay-500">
            <span class="flex items-center gap-2">
                <i class="fas fa-shield-alt text-green-500 text-lg"></i> Secure login
            </span>
            <span class="flex items-center gap-2">
                <i class="fas fa-bolt text-yellow-500 text-lg"></i> Fast response
            </span>
        </div>
    </div>

    {{-- REGISTER FORM --}}
    <div id="registerForm"
         class="auth-form {{ $mode === 'register' ? 'auth-form-active' : '' }}">

        {{-- HEADER --}}
        <div class="text-center mb-8">
            <h2 class="text-4xl font-semibold text-gray-900 animate-slide-up">
                Create your account
            </h2>
            <p class="text-gray-500 text-sm mt-2 animate-fade-in delay-100">
                Choose your role to personalize your ClassMonitor dashboard.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            {{-- ROLE --}}
            <div class="animate-slide-up delay-150">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">I am a</label>

                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="student"
                               class="peer hidden"
                               {{ old('role', 'student') === 'student' ? 'checked' : '' }}>

                        <div
                            class="flex flex-col items-center justify-center rounded-2xl border border-gray-300 
                                   bg-gray-50 px-3 py-3 text-sm 
                                   peer-checked:border-[#8DDE66] peer-checked:bg-[#8DDE66]/15
                                   peer-checked:text-[#15752c] transition-all duration-200 shadow-sm">
                            <i class="fas fa-user-graduate text-base mb-0.5"></i>
                            Student
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="teacher"
                               class="peer hidden"
                               {{ old('role') === 'teacher' ? 'checked' : '' }}>

                        <div
                            class="flex flex-col items-center justify-center rounded-2xl border border-gray-300 
                                   bg-gray-50 px-3 py-3 text-sm 
                                   peer-checked:border-[#8DDE66] peer-checked:bg-[#8DDE66]/15
                                   peer-checked:text-[#15752c] transition-all duration-200 shadow-sm">
                            <i class="fas fa-chalkboard-teacher text-base mb-0.5"></i>
                            Teacher
                        </div>
                    </label>
                </div>
            </div>

            {{-- FULL NAME --}}
            <div class="animate-slide-up delay-200">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Full Name</label>

                <div class="relative">
                    <i class="fas fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-[#8DDE66] text-base"></i>

                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full pl-11 pr-4 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-[#8DDE66] focus:border-[#8DDE66]
                                  hover:border-[#8DDE66] transition-all duration-200"
                           placeholder="Enter your full name">
                </div>
            </div>

            {{-- EMAIL --}}
            <div class="animate-slide-up delay-225">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Email Address</label>

                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-[#8DDE66] text-base"></i>

                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full pl-11 pr-4 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-[#8DDE66] focus:border-[#8DDE66]
                                  hover:border-[#8DDE66] transition-all duration-200"
                           placeholder="you@example.com">
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="animate-slide-up delay-250">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Password</label>

                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-[#8DDE66] text-base"></i>

                    <input type="password" name="password" id="register_password"
                           class="w-full pl-11 pr-11 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-[#8DDE66] focus:border-[#8DDE66]
                                  hover:border-[#8DDE66] transition-all duration-200"
                           placeholder="Create a password" required>

                    <button type="button"
                            onclick="toggleRegisterPassword('register_password', 'registerPasswordToggleIcon')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#15752c]">
                        <i class="fas fa-eye text-base" id="registerPasswordToggleIcon"></i>
                    </button>
                </div>
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="animate-slide-up delay-275">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Confirm Password</label>

                <div class="relative">
                    <i class="fas fa-check absolute left-3 top-1/2 -translate-y-1/2 text-[#8DDE66] text-base"></i>

                    <input type="password" name="password_confirmation" id="register_password_confirmation"
                           class="w-full pl-11 pr-11 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-[#8DDE66] focus:border-[#8DDE66]
                                  hover:border-[#8DDE66] transition-all duration-200"
                           placeholder="Re-enter your password" required>

                    <button type="button"
                            onclick="toggleRegisterPassword('register_password_confirmation', 'registerConfirmToggleIcon')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#15752c]">
                        <i class="fas fa-eye text-base" id="registerConfirmToggleIcon"></i>
                    </button>
                </div>
            </div>

            {{-- SUBMIT --}}
            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-[#8DDE66] to-[#5FBF3B] text-white font-semibold 
                       rounded-2xl shadow-lg hover:shadow-xl hover:scale-[1.03]
                       transition-all duration-300 animate-soft-pop delay-300 text-lg">
                <i class="fas fa-user-plus mr-2"></i>
                Create account
            </button>

            {{-- SWITCH TO LOGIN --}}
            <p class="text-center text-sm text-gray-600 animate-fade-in delay-350">
                Already have an account?
                <a href="{{ route('login') }}"
                   data-auth-toggle="to-login"
                   class="font-semibold text-blue-600 hover:text-[#15752c] hover:underline">
                    Log in instead
                </a>
            </p>
        </form>
    </div>

    {{-- FORGOT PASSWORD FORM (BLUE THEME) --}}
    <div id="forgotForm"
         class="auth-form {{ $mode === 'forgot' ? 'auth-form-active' : '' }}">

        {{-- HEADER --}}
        <div class="text-center mb-8">
            <h2 class="text-4xl font-semibold text-gray-900 animate-slide-up">
                Forgot password?
            </h2>
            <p class="text-gray-500 text-sm mt-2 max-w-md mx-auto animate-fade-in delay-100">
                Enter the email address associated with your account and we’ll send you a secure link
                to reset your password.
            </p>
        </div>

        {{-- FORGOT FORM --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-7">
            @csrf

            {{-- EMAIL --}}
            <div class="animate-slide-up delay-150">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Email Address</label>

                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-blue-500 text-base"></i>

                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full pl-11 pr-4 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-500
                                  hover:border-blue-400 transition-all duration-200"
                           placeholder="you@example.com">
                </div>

                @error('email')
                    <p class="text-sm text-red-500 mt-1 flex items-center gap-1 animate-fade-in">
                        <i class="fas fa-circle-exclamation text-[13px]"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- SEND LINK BUTTON --}}
            <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold 
                           rounded-2xl shadow-lg hover:shadow-xl hover:scale-[1.03]
                           transition-all duration-300 animate-soft-pop delay-200 text-lg">
                <i class="fas fa-paper-plane mr-2"></i>
                Send reset link
            </button>

            {{-- BACK TO SIGN IN (NO RELOAD) --}}
            <div class="mt-4 text-center animate-fade-in delay-300">
                <p class="text-sm text-gray-600">
                    Remember your password?
                    <a href="{{ route('login') }}"
                       data-auth-toggle="to-login"
                       class="font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                        Back to Sign In
                    </a>
                </p>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-200 text-xs text-gray-400 text-center animate-fade-in delay-400">
            If you don’t see the email in your inbox, check your spam folder or contact your administrator.
        </div>
    </div>

    {{-- JS: password toggles --}}
    <script>
        function toggleLoginPassword() {
            const input = document.getElementById("login_password");
            const icon  = document.getElementById("loginToggleIcon");
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }

        function toggleRegisterPassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }
    </script>

</x-guest-layout>
