<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ClassMonitor – @yield('title', 'Welcome')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-bgSoft2 flex items-center justify-center">

<div class="w-full max-w-5xl mx-4 lg:mx-0 bg-white rounded-3xl shadow-2xl overflow-hidden flex">

    {{-- Left illustration panel --}}
    <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-brandIndigo via-brandSky to-brandAmber
                items-center justify-center relative overflow-hidden">

        {{-- Abstract shapes --}}
        <div class="absolute -top-32 -left-20 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative px-8">
            <div class="w-64 h-64 rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center shadow-xl animate-pulse">
                {{-- Illustration placeholder --}}
                <img src="{{ asset('images/login-illustration.svg') }}"
                     onerror="this.style.display='none'"
                     alt="ClassMonitor Illustration"
                     class="w-48 h-48 object-contain">
                <span class="text-5xl" style="display:none;">📚</span>
            </div>

            <h2 class="mt-6 text-white text-2xl font-semibold">
                Welcome to ClassMonitor
            </h2>
            <p class="mt-2 text-white/80 text-sm leading-relaxed">
                Real-time classroom attendance and quiz management for modern teachers and students.
            </p>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="w-full md:w-1/2 bg-white px-8 py-10 lg:px-10 flex flex-col justify-center">
        <div class="mb-6">
            <div class="inline-flex items-center rounded-full bg-bgSoft px-3 py-1 text-[11px] text-brandIndigo font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-brandEmerald mr-2 animate-ping"></span>
                ClassMonitor Portal
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-brandSlate">
                @yield('heading', 'Welcome back')
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                @yield('subheading', 'Log in to continue to your classroom.')
            </p>
        </div>

        {{-- Auth card --}}
        <div class="bg-bgSoft rounded-2xl shadow-inner p-6">
            {{ $slot }}
        </div>

        <p class="mt-6 text-[11px] text-gray-400 text-center">
            By continuing, you agree to the ClassMonitor terms and privacy policy.
        </p>
    </div>
</div>

</body>
</html>
