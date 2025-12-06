<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassMonitor | Auth</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans min-h-screen bg-gray-100 flex items-center justify-center relative px-4">

    {{-- GLOBAL FLOATING SHAPES --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-24 left-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl animate-float-slow"></div>
        <div
            class="absolute bottom-20 right-32 w-72 h-72 bg-secondary-light/20 rounded-full blur-3xl animate-pulse-glow">
        </div>
        <div class="absolute top-1/3 right-1/4 w-56 h-56 bg-brandSky/10 rounded-full blur-2xl animate-float-slow"></div>
    </div>

    {{-- MAIN AUTH CONTAINER --}}
    <div class="relative w-full max-w-6xl bg-white/70 backdrop-blur-xl rounded-4xl shadow-strong 
                overflow-hidden flex flex-col lg:flex-row" style="max-height: 820px;">

        {{-- LEFT PANEL --}}
        <section class="lg:w-1/2 bg-gradient-primary text-white px-12 py-20 flex flex-col 
                        items-center justify-center text-center relative">

            {{-- Glow Overlays --}}
            <div class="absolute inset-0 opacity-40 pointer-events-none">
                <div class="absolute top-0 left-0 w-52 h-52 bg-white/10 rounded-full blur-3xl animate-float-slow"></div>
                <div class="absolute bottom-0 right-0 w-72 h-72 bg-white/20 rounded-full blur-3xl animate-pulse-glow">
                </div>
            </div>

            <div
                class="relative z-10 w-full max-w-xl mx-auto flex flex-col items-center text-center animate-fade-in pb-8">

                {{-- BRAND LOGO --}}
                <div class="flex items-center space-x-4 mt-6 mb-5">
                    <img src="{{ asset('images/cm-logo.png') }}" class="w-14 h-14 rounded-xl drop-shadow-lg" alt="Logo">

                    <div class="flex flex-col text-left leading-tight">
                        <h1 class="text-xl font-bold tracking-tight">ClassMonitor</h1>
                        <p class="text-white/80 text-sm">Digitizing Attendance & Quiz</p>
                    </div>
                </div>

                {{-- HEADING --}}
                <h2 class="text-[32px] font-semibold leading-tight max-w-lg mt-4 mb-4 drop-shadow animate-slide-up">
                    A Modern Solution for <br>Attendance & Interactive Quizzes
                </h2>

                {{-- Illustration --}}
                <img src="{{ asset('images/login-illustration.png') }}"
                    class="w-72 mx-auto drop-shadow-2xl animate-float-slow mt-4 mb-8" alt="Illustration">

                {{-- FEATURE CARDS --}}
                <div class="w-full max-w-lg flex justify-between space-x-5 mt-2 mb-6 animate-slide-up delay-150">

                    <div class="bg-white/15 backdrop-blur-sm border border-white/20 rounded-3xl 
                                px-5 py-4 w-1/2 flex space-x-4 shadow-lg hover:scale-[1.03] 
                                transition-all duration-300">
                        <div class="w-11 h-11 rounded-xl bg-brandSky flex items-center justify-center shadow-md">
                            <i class="fas fa-qrcode text-white text-lg"></i>
                        </div>
                        <div class="text-left leading-tight">
                            <p class="text-base font-semibold">QR Attendance</p>
                            <p class="text-[13px] text-white/80">Secure & Fast</p>
                        </div>
                    </div>

                    <div class="bg-white/15 backdrop-blur-sm border border-white/20 rounded-3xl 
                                px-5 py-4 w-1/2 flex space-x-4 shadow-lg hover:scale-[1.03] 
                                transition-all duration-300">
                        <div class="w-11 h-11 rounded-xl bg-brandEmerald flex items-center justify-center shadow-md">
                            <i class="fas fa-bolt text-white text-lg"></i>
                        </div>
                        <div class="text-left leading-tight">
                            <p class="text-base font-semibold">Live Quizzes</p>
                            <p class="text-[13px] text-white/80">Instant scoring</p>
                        </div>
                    </div>

                </div>

                {{-- Testimonial --}}
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-3xl px-7 py-5 
                            w-full max-w-lg shadow-lg animate-slide-up delay-300">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shadow-md">
                            <i class="fas fa-quote-left text-white text-base"></i>
                        </div>
                        <div class="text-left leading-snug">
                            <p class="text-base font-medium">
                                “ClassMonitor reduced admin work by <span class="font-semibold">70%</span>.”
                            </p>
                            <p class="text-[13px] text-white/70 mt-1">
                                — Prof. Ishfak Akbar, University of Tech
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- RIGHT PANEL (Yellow glow removed) --}}
        <section class="lg:w-1/2 flex items-center justify-center px-16 py-16 relative overflow-hidden">

            {{-- Subtle geometric pattern --}}
            <div class="absolute inset-0 opacity-[0.08] bg-[radial-gradient(circle_at_1px_1px,black_1px,transparent_0)] 
                        bg-[size:22px_22px]"></div>

            {{-- Removed Yellow Glow Shape --}}

            {{-- Login Content --}}
            <div class="relative z-10 w-full max-w-sm animate-fade-in">
                {{ $slot }}
            </div>

        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>

</body>

</html>