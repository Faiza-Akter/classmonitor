<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ClassMonitor') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white text-slate-900 dark:bg-white dark:text-slate-900">
@php
    $cmBlue   = '#2463EB';
    $cmGreen  = '#8BDE63';
    $cmYellow = '#EDB70A';
@endphp

<div class="min-h-screen bg-white">

    {{-- Solid white navbar --}}
    <header class="sticky top-0 z-50 bg-white">
        @include('layouts.navigation')
    </header>

    {{-- Light global page background (not dark) --}}
    <div class="relative">
        <div class="pointer-events-none absolute inset-0 -z-10"
             style="background:
                radial-gradient(900px 520px at 15% 10%, rgba(36,99,235,.10), transparent 60%),
                radial-gradient(800px 520px at 90% 20%, rgba(237,183,10,.08), transparent 55%),
                radial-gradient(1000px 700px at 70% 95%, rgba(139,222,99,.08), transparent 60%),
                linear-gradient(180deg, #ffffff, #f8fafc);">
        </div>

        {{-- Optional Page Heading --}}
        @isset($header)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="px-5 sm:px-6 py-4">
                        {{ $header }}
                    </div>
                </div>
            </div>
        @endisset

        {{-- ✅ SLOT FALLBACK (keeps slot + supports @extends/@section) --}}
        @php
            if (!isset($slot) && $__env->hasSection('content')) {
                $slot = $__env->yieldContent('content');
            }
        @endphp

        <main class="relative">
            {!! $slot !!}
        </main>
    </div>
</div>
</body>
</html>
