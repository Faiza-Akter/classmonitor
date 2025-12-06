<x-guest-layout>

    {{-- STATUS MESSAGE --}}
    @if (session('status'))
        <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-300 rounded-xl text-sm text-emerald-700 
                    flex items-center gap-2 animate-soft-pop">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <h2 class="text-4xl font-semibold text-gray-900 animate-slide-up">Welcome Back</h2>
        <p class="text-gray-500 text-sm mt-1 animate-fade-in delay-100">
            Log in to continue your class activities.
        </p>
    </div>

    {{-- LOGIN FORM --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-7">
        @csrf

        {{-- EMAIL --}}
        <div class="animate-slide-up delay-150">
            <label class="text-sm font-semibold text-gray-700 mb-2 block">Email Address</label>

            <div class="relative">
                <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-yellow-500 text-sm"></i>

                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full pl-12 pr-4 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                              focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 
                              focus:shadow-[0_0_12px_rgba(250,204,21,0.35)]
                              hover:border-yellow-400 transition-all duration-200"
                       placeholder="you@example.com" required>
            </div>

            @error('email')
                <p class="text-xs text-red-500 mt-1 flex items-center gap-1 animate-fade-in">
                    <i class="fas fa-circle-exclamation text-[11px]"></i>{{ $message }}
                </p>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="animate-slide-up delay-200">
            <label class="text-sm font-semibold text-gray-700 mb-2 block">Password</label>

            <div class="relative">
                <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-yellow-500 text-sm"></i>

                <input type="password" name="password" id="password"
                       class="w-full pl-12 pr-12 py-3.5 text-base rounded-2xl border border-gray-300 bg-gray-50
                              focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500
                              focus:shadow-[0_0_12px_rgba(250,204,21,0.35)]
                              hover:border-yellow-400 transition-all duration-200"
                       placeholder="Your password" required>

                {{-- Eye Button --}}
                <button type="button" onclick="togglePassword()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-600">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>

            {{-- REMEMBER + FORGOT --}}
            <div class="flex justify-between items-center mt-3 animate-fade-in delay-300">

                {{-- REMEMBER --}}
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 text-yellow-500 rounded-md border-gray-300 focus:ring-yellow-400">
                    Remember me
                </label>

                {{-- FORGOT --}}
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-[13px] font-semibold text-blue-600 hover:text-yellow-600 hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            @error('password')
                <p class="text-xs text-red-500 mt-1 flex items-center gap-1 animate-fade-in">
                    <i class="fas fa-circle-exclamation text-[11px]"></i>{{ $message }}
                </p>
            @enderror
        </div>

        {{-- BUTTON --}}
        <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-yellow-500 to-yellow-400 text-white 
                       font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:scale-[1.02]
                       transform transition-all duration-300 animate-soft-pop delay-300 text-lg">
            <i class="fas fa-arrow-right-to-bracket mr-2"></i>
            Sign In
        </button>

        {{-- SIGN UP --}}
        <p class="text-center text-sm text-gray-600 mt-6 animate-fade-in delay-400">
            Don’t have an account?
            <a href="{{ route('register') }}"
               class="font-semibold text-blue-600 hover:text-yellow-700 hover:underline">
                Sign up
            </a>
        </p>

    </form>

    {{-- FOOTER --}}
    <div class="flex items-center justify-center gap-6 text-[12px] text-gray-500 mt-8 pt-6 
                border-t border-gray-200 animate-fade-in delay-500">
        <span class="flex items-center gap-1">
            <i class="fas fa-shield-alt text-green-500"></i> Secure login
        </span>
        <span class="flex items-center gap-1">
            <i class="fas fa-bolt text-yellow-500"></i> Fast response
        </span>
    </div>

    {{-- JS: Toggle Password Visibility --}}
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }
    </script>

</x-guest-layout>
