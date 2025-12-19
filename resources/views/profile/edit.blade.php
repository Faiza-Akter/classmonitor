@extends('layouts.app')

@section('content')
    @php
        $cmBlue = '#2463EB';
        $cmGreen = '#8BDE63';
        $cmYellow = '#EDB70A';
        $cmRed = '#EF4444';
    @endphp

    <div class="min-h-[calc(100vh-88px)] text-slate-900 relative overflow-x-hidden" style="background:#2463EB;">
        {{-- Top accent strip --}}
        <div class="h-[6px] w-full"
            style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 overflow-x-hidden">

            {{-- Header (NO header card) --}}
            <div class="cm-animate-in">
                <p class="text-xs font-bold tracking-widest uppercase text-white/80">Account</p>
                <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white">
                    Profile Settings
                </h1>
                <p class="mt-2 text-sm sm:text-base text-white/85">
                    Update your information, password, and security settings.
                </p>
            </div>

            {{-- Content wrapper --}}
            <div class="mt-6 lg:mt-8 space-y-5
                            bg-transparent text-slate-900
                            dark:text-slate-900
                            dark:[&_*]:text-slate-900
                            dark:[&_p]:text-slate-600
                            dark:[&_label]:text-slate-700
                            dark:[&_input]:bg-white
                            dark:[&_input]:text-slate-900
                            dark:[&_input]:border-slate-300
                            dark:[&_button]:text-inherit">

                {{-- Update Profile Info --}}
                <div class="cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
                    <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Profile Information</h2>
                            <p class="mt-1 text-sm text-slate-600">Update your name and email address.</p>
                        </div>

                        <span
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-white">
                            <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                            Profile
                        </span>
                    </div>

                    <div class="cm-panel-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="cm-panel cm-reveal" style="border-color: rgba(237,183,10,.22);">
                    <div class="cm-panel-head" style="background: rgba(237,183,10,.10);">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Update Password</h2>
                            <p class="mt-1 text-sm text-slate-600">Use a strong password to keep your account secure.</p>
                        </div>

                        <span
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-white">
                            <span class="w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                            Security
                        </span>
                    </div>

                    <div class="cm-panel-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="cm-panel cm-reveal" style="border-color: rgba(239,68,68,.28);">
                    <div class="cm-panel-head" style="background: rgba(239,68,68,.12);">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Delete Account</h2>
                            <p class="mt-1 text-sm text-slate-600">Permanently delete your account and associated data.</p>
                        </div>

                        <span
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-white">
                            <span class="w-2 h-2 rounded-full" style="background: {{ $cmRed }};"></span>
                            Danger
                        </span>
                    </div>

                    <div class="cm-panel-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

            <div class="h-8"></div>
        </div>

        <style>
            html,
            body {
                overflow-x: hidden;
            }

            .cm-animate-in {
                opacity: 0;
                transform: translateY(10px);
                animation: cmIn .55s ease forwards;
            }

            @keyframes cmIn {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .cm-reveal {
                opacity: 0;
                transform: translateY(14px);
            }

            .cm-reveal.cm-in {
                opacity: 1;
                transform: translateY(0);
                transition: opacity .55s ease, transform .55s ease;
            }

            .cm-panel {
                border: 1px solid rgba(226, 232, 240, 1);
                background: #fff;
                border-radius: 1.5rem;
                box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
                overflow: hidden;
            }

            .cm-panel-head {
                padding: 1.25rem 1.25rem 1rem 1.25rem;
                border-bottom: 1px solid rgba(226, 232, 240, 1);
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
            }

            .cm-panel-body {
                padding: 1.25rem;
            }

            /* Make inputs consistent inside partials */
            .cm-panel :is(input, select, textarea) {
                border-radius: 14px !important;
            }

            /*
              ✅ Fix: Your green rule was matching ALL submit buttons, including Delete.
              We limit green styling to non-danger forms by EXCLUDING:
              - forms with action containing "delete"
              - buttons inside the delete panel block (.cm-danger)
              - buttons that already have .danger
            */

            /* Save buttons (green) — but NOT inside delete forms */
            .cm-panel form:not([action*="delete"]):not([action*="destroy"]) :is(button[type="submit"], .btn, .btn-primary, .primary) {
                background: {{ $cmGreen }} !important;
                border-color: rgba(139, 222, 99, .55) !important;
                color: #0f172a !important;
                font-weight: 950 !important;
                border-width: 2px !important;
                border-radius: 9999px !important;
                padding: .62rem 1rem !important;
                box-shadow: 0 6px 16px rgba(15, 23, 42, .10) !important;
                transition: transform .15s ease, box-shadow .2s ease, filter .2s ease !important;
            }

            .cm-panel form:not([action*="delete"]):not([action*="destroy"]) :is(button[type="submit"], .btn, .btn-primary, .primary):hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 12px 26px rgba(15, 23, 42, .14) !important;
                filter: saturate(1.06) !important;
            }

            .cm-panel form:not([action*="delete"]):not([action*="destroy"]) :is(button[type="submit"], .btn, .btn-primary, .primary):focus {
                outline: none !important;
                box-shadow: 0 0 0 4px rgba(139, 222, 99, .22), 0 12px 26px rgba(15, 23, 42, .12) !important;
            }

            /* 🔴 Delete Account (danger) — stronger + more specific, will override green */
            .cm-panel form[action*="delete"] :is(button[type="submit"], .btn, .btn-danger, .danger),
            .cm-panel form[action*="destroy"] :is(button[type="submit"], .btn, .btn-danger, .danger) {
                background: {{ $cmRed }} !important;
                border-color: rgba(239, 68, 68, .60) !important;
                color: #ffffff !important;
                font-weight: 950 !important;
                border-width: 2px !important;
                border-radius: 9999px !important;
                padding: .62rem 1rem !important;
                box-shadow: 0 6px 16px rgba(15, 23, 42, .12) !important;
                transition: transform .15s ease, box-shadow .2s ease, filter .2s ease !important;
            }

            .cm-panel form[action*="delete"] :is(button[type="submit"], .btn, .btn-danger, .danger):hover,
            .cm-panel form[action*="destroy"] :is(button[type="submit"], .btn, .btn-danger, .danger):hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 12px 26px rgba(239, 68, 68, .35) !important;
                filter: saturate(1.05) !important;
            }

            .cm-panel form[action*="delete"] :is(button[type="submit"], .btn, .btn-danger, .danger):focus,
            .cm-panel form[action*="destroy"] :is(button[type="submit"], .btn, .btn-danger, .danger):focus {
                outline: none !important;
                box-shadow: 0 0 0 4px rgba(239, 68, 68, .25), 0 12px 26px rgba(15, 23, 42, .14) !important;
            }
        </style>

        <script>
            (function () {
                const items = Array.from(document.querySelectorAll('.cm-reveal'));
                const io = new IntersectionObserver((entries) => {
                    entries.forEach(e => {
                        if (e.isIntersecting) {
                            e.target.classList.add('cm-in');
                            io.unobserve(e.target);
                        }
                    });
                }, { threshold: 0.10 });
                items.forEach(el => io.observe(el));
            })();
        </script>
    </div>
@endsection
