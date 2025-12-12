<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ClassMonitor') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- keep your existing big Tailwind fallback CSS block here --}}
        <style>
            /* ... original inline Tailwind fallback CSS ... */
        </style>
    @endif
</head>

<body class="min-h-screen text-slate-900">

    <div class="relative min-h-screen overflow-hidden">

        {{-- HERO BACKGROUND IMAGE --}}
        <div class="absolute inset-0" style="
            background-image: url('{{ asset('images/welcome-hero-bg.png') }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: right center;
         ">
        </div>

        {{-- TRANSPARENT NAVBAR --}}
        <header class="absolute top-0 left-0 right-0 z-20">
            <div class="flex items-center justify-between px-4 lg:px-8 py-4">

                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/cm-logo.png') }}" alt="ClassMonitor Logo"
                        class="w-10 h-10 rounded-xl bg-white/90 p-1 shadow-sm">
                    <div class="leading-tight">
                        <p class="font-semibold text-base lg:text-lg tracking-tight text-white drop-shadow">
                            ClassMonitor
                        </p>
                        <p class="text-[11px] text-white/85 drop-shadow">
                            Digitizing Attendance &amp; Quiz
                        </p>
                    </div>
                </div>

                {{-- Auth buttons --}}
                @if (Route::has('login'))
                    <div class="flex items-center gap-3 text-sm">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-1.5 rounded-full bg-white text-emerald-700 font-semibold shadow-sm
                                                  hover:bg-emerald-500 hover:text-white transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-full border-2 border-emerald-500 text-emerald-700 font-medium
                                                  bg-white/80 backdrop-blur hover:bg-emerald-50 transition">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-full bg-gradient-to-r from-[#8CDE66] to-[#5048e5ff]
                                                              text-white font-semibold shadow-md hover:shadow-lg
                                                              hover:from-[#8CDE66] hover:to-[#5048e5ff] transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </header>

        {{-- HERO CONTENT --}}
        <main class="relative z-10">
            <div class="max-w-7xl mx-auto px-3 lg:px-10 pt-28 lg:pt-32 pb-16 lg:pb-24">
                <div
                    class="grid grid-cols-1 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] gap-10 lg:gap-20 items-center">

                    {{-- LEFT: spacer so illustration on BG shows nicely --}}
                    <section class="hidden lg:block h-[460px]"></section>

                    {{-- RIGHT: text + buttons + 2 features --}}
                    <section class="w-full lg:col-start-2 lg:-translate-x-6 transition-transform duration-300">
                        <div class="w-full max-w-2xl">

                            <h1
                                class="text-3xl lg:text-5xl font-semibold leading-tight mb-4 text-slate-900 drop-shadow-sm">
                                Make Attendance &amp; Quizzes
                                <span class="text-sky-600">simple</span> and
                                <span class="text-[#8CDE66]">engaging</span>.
                            </h1>

                            <p class="text-sm lg:text-lg text-slate-700 mb-7">
                                ClassMonitor helps teachers manage QR-based attendance, launch live quizzes,
                                and keep students engaged with real-time results and analytics.
                            </p>

                            {{-- CTA buttons --}}
                            <div class="flex w-full flex-wrap items-center gap-3 mb-8">
                                <a href="{{ route('login') }}" class="flex-1 px-6 py-3 rounded-2xl bg-gradient-to-r from-[#8CDE66] to-[#5048e5ff]
                              text-white font-semibold text-sm lg:text-base shadow-md hover:shadow-lg
                              hover:from-[#8CDE66] hover:to-[#5048e5ff]
                              transition flex items-center justify-center gap-2 text-center">
                                    <span>Get started</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="flex-1 px-6 py-3 rounded-2xl bg-white/90 text-sky-700 font-semibold text-sm lg:text-base
                                          border border-sky-200 shadow-sm hover:bg-sky-50 transition
                                          text-center flex items-center justify-center">
                                        Try as new teacher
                                    </a>
                                @endif
                            </div>

                            {{-- TWO FEATURE CARDS --}}
                            <div class="grid w-full grid-cols-1 sm:grid-cols-2 gap-5 text-sm lg:text-base">

                                <div class="w-full flex items-start gap-3 bg-sky-50/95 border border-sky-100
                                rounded-2xl px-4 py-3 shadow-sm backdrop-blur">
                                    <div class="w-9 h-9 rounded-2xl bg-sky-100 flex items-center justify-center">
                                        <i class="fas fa-qrcode text-sky-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">QR Attendance</p>
                                        <p class="text-xs lg:text-sm text-slate-500">
                                            Fast, contactless student check-in.
                                        </p>
                                    </div>
                                </div>

                                <div class="w-full flex items-start gap-3 bg-amber-50/95 border border-amber-100
                                rounded-2xl px-4 py-3 shadow-sm backdrop-blur">
                                    <div class="w-9 h-9 rounded-2xl bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-bolt text-yellow-500 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Live Quizzes</p>
                                        <p class="text-xs lg:text-sm text-slate-500">
                                            Instant scoring and leaderboards.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
</body>

</html>
