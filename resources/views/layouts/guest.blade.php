@props(['mode' => 'login']) {{-- "login" (default) or "register" --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassMonitor | Auth</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans min-h-screen auth-bg flex items-center justify-center relative px-4">

    {{-- GLOBAL FLOATING SHAPES --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-24 left-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl animate-float-slow"></div>
        <div
            class="absolute bottom-20 right-32 w-72 h-72 bg-secondary-light/20 rounded-full blur-3xl animate-pulse-glow">
        </div>
        <div class="absolute top-1/3 right-1/4 w-56 h-56 bg-brandSky/10 rounded-full blur-2xl animate-float-slow"></div>
    </div>

    {{-- MAIN AUTH CONTAINER --}}
    <div id="authContainer" data-mode="{{ $mode }}" class="auth-container auth-card-shadow relative w-full max-w-6xl 
            bg-white/80 backdrop-blur-xl rounded-4xl 
            overflow-hidden flex flex-col lg:flex-row
            {{ $mode === 'register' ? 'mode-register' : 'mode-login' }}" style="max-height: 870px;">


        {{-- LEFT PANEL --}}
        <section class="auth-panel auth-left lg:w-1/2 bg-blue-600 text-white px-12 py-20 flex flex-col 
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

        {{-- RIGHT PANEL --}}
        <section
            class="auth-panel auth-right lg:w-1/2 flex items-center justify-center px-16 py-16 relative overflow-hidden">

            <div class="relative z-10 w-full max-w-sm auth-forms-wrapper">
                {{ $slot }}
            </div>

        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>

    {{-- PANEL + FORM MODE JS --}}
    {{-- PANEL + FORM MODE JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('authContainer');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const forgotForm = document.getElementById('forgotForm'); // may or may not exist

            if (!container) return;

            // track which panel position is active (for sliding left/right)
            let panelMode = (container.dataset.mode === 'register') ? 'register' : 'login';

            const applyPanelMode = () => {
                container.classList.toggle('mode-login', panelMode === 'login');
                container.classList.toggle('mode-register', panelMode === 'register');
            };

            const setMode = (mode) => {
                // for login/register we also move the big panels
                if (mode === 'login' || mode === 'register') {
                    panelMode = mode;
                    applyPanelMode();
                }

                // show/hide individual forms
                if (loginForm) {
                    loginForm.classList.toggle('auth-form-active', mode === 'login');
                }
                if (registerForm) {
                    registerForm.classList.toggle('auth-form-active', mode === 'register');
                }
                if (forgotForm) {
                    forgotForm.classList.toggle('auth-form-active', mode === 'forgot');
                }

                container.dataset.mode = mode;
            };

            // initial state
            applyPanelMode();
            const initialMode = container.dataset.mode || 'login';
            setMode(initialMode);

            // links with data-auth-toggle
            document.querySelectorAll('[data-auth-toggle]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = link.getAttribute('data-auth-toggle');

                    if (target === 'to-register') setMode('register');
                    if (target === 'to-login') setMode('login');
                    if (target === 'to-forgot') setMode('forgot'); // no panel slide, just form swap
                });
            });
        });
    </script>


</body>

</html>