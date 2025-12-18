<nav x-data="{ open: false }" class="w-full">
    @php
        $cmBlue   = '#2463EB';
        $cmGreen  = '#8BDE63';
        $cmYellow = '#EDB70A';

        $user = Auth::user();
        $role = $user?->role ?? null;

        // Role-aware destinations
        $dashUrl = $role === 'teacher'
            ? route('teacher.dashboard')
            : route('student.dashboard');

        // Attendance link (teacher vs student)
        $attendanceUrl = $role === 'teacher'
            ? route('attendance.index')
            : route('attendance.join.form');

        // Quizzes link (teacher vs student)
        $quizzesUrl = $role === 'teacher'
            ? route('quizzes.index')
            : route('student.quizzes.history');

        $roleLabel = $role ? ucfirst($role) : 'Account';

        // Active states (simple + reliable)
        $isDash = request()->routeIs('teacher.dashboard') || request()->routeIs('student.dashboard') || request()->routeIs('dashboard');
        $isAttendance = $role === 'teacher'
            ? request()->routeIs('attendance.*') || request()->is('attendance*')
            : request()->routeIs('attendance.join.*') || request()->routeIs('student.attendance.*') || request()->is('attendance/join') || request()->is('student/attendance');
        $isQuizzes = $role === 'teacher'
            ? request()->routeIs('quizzes.*') || request()->is('quizzes*')
            : request()->routeIs('student.quizzes.*') || request()->is('student/quizzes*') || request()->routeIs('quizzes.play') || request()->routeIs('quizzes.result');
        $linkBase = "px-4 py-2 rounded-xl text-sm font-semibold transition";
    @endphp

    {{-- Solid white top bar --}}
    <div class="bg-white border-b border-slate-200 shadow-[0_1px_0_rgba(15,23,42,0.04)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                {{-- Brand --}}
                <div class="flex items-center gap-8">
                    <a href="{{ $dashUrl }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl grid place-items-center border border-slate-200 bg-white">
                            <span class="text-sm font-extrabold" style="color: {{ $cmBlue }};">CM</span>
                        </div>
                        <div class="leading-tight">
                            <p class="text-sm font-extrabold text-slate-900">ClassMonitor</p>
                            <p class="text-[11px] text-slate-500 -mt-0.5">{{ $roleLabel }}</p>
                        </div>
                    </a>

                    {{-- Desktop links --}}
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ $dashUrl }}"
                           class="{{ $linkBase }} {{ $isDash ? 'text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50' }}"
                           style="{{ $isDash ? "background:$cmBlue;" : '' }}">
                            Dashboard
                        </a>

                        <a href="{{ $attendanceUrl }}"
                           class="{{ $linkBase }} {{ $isAttendance ? 'text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50' }}"
                           style="{{ $isAttendance ? "background:$cmGreen; color:#0B1B0F;" : '' }}">
                            <span class="inline-block w-2 h-2 rounded-full mr-2 align-middle" style="background:{{ $cmGreen }};"></span>
                            Attendance
                        </a>

                        <a href="{{ $quizzesUrl }}"
                           class="{{ $linkBase }} {{ $isQuizzes ? 'text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50' }}"
                           style="{{ $isQuizzes ? "background:$cmYellow; color:#3a2b00;" : '' }}">
                            <span class="inline-block w-2 h-2 rounded-full mr-2 align-middle" style="background:{{ $cmYellow }};"></span>
                            Quizzes
                        </a>

                        {{-- Student-only quick link --}}
                        @if($role === 'student')
                            <a href="{{ route('student.attendance.history') }}"
                               class="{{ $linkBase }} text-slate-700 hover:bg-slate-50">
                                History
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Right --}}
                <div class="hidden sm:flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white">
                        <span class="inline-block w-2 h-2 rounded-full" style="background:{{ $cmGreen }};"></span>
                        <span class="text-xs font-semibold text-slate-600">Online</span>
                    </div>

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-3 px-3 py-2 rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition focus:outline-none">
                                <div class="w-9 h-9 rounded-xl grid place-items-center text-white font-extrabold"
                                     style="background: {{ $cmBlue }};">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>

                                <div class="text-left">
                                    <div class="text-sm font-bold text-slate-900 leading-tight">{{ $user->name }}</div>
                                    <div class="text-[11px] text-slate-500 leading-tight">{{ $roleLabel }}</div>
                                </div>

                                <svg class="fill-current h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3">
                                <p class="text-xs font-bold text-slate-500">Signed in as</p>
                                <p class="text-sm font-extrabold text-slate-900 truncate">{{ $user->email }}</p>
                            </div>

                            <div class="h-px bg-slate-200"></div>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="h-px bg-slate-200"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Mobile --}}
                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:shadow-md transition"
                            aria-label="Open Menu">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        {{-- Thin theme underline --}}
        <div class="h-[3px] w-full"
             style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

        {{-- Mobile menu --}}
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="px-4 pb-4 pt-3 space-y-2 bg-white border-t border-slate-200">
                <a href="{{ $dashUrl }}" class="block px-4 py-3 rounded-2xl font-semibold border border-slate-200 bg-white text-slate-900">
                    Dashboard
                </a>

                <a href="{{ $attendanceUrl }}" class="block px-4 py-3 rounded-2xl font-semibold border border-slate-200 bg-white text-slate-900">
                    <span class="inline-block w-2 h-2 rounded-full mr-2 align-middle" style="background:{{ $cmGreen }};"></span>
                    Attendance
                </a>

                @if($role === 'student')
                    <a href="{{ route('student.attendance.history') }}" class="block px-4 py-3 rounded-2xl font-semibold border border-slate-200 bg-white text-slate-900">
                        Attendance History
                    </a>
                @endif

                <a href="{{ $quizzesUrl }}" class="block px-4 py-3 rounded-2xl font-semibold border border-slate-200 bg-white text-slate-900">
                    <span class="inline-block w-2 h-2 rounded-full mr-2 align-middle" style="background:{{ $cmYellow }};"></span>
                    Quizzes
                </a>

                <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-4">
                    <p class="text-sm font-extrabold text-slate-900">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $roleLabel }}</p>

                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="flex-1 text-center px-4 py-2 rounded-xl font-semibold text-white" style="background:{{ $cmBlue }};">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white text-slate-900">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</nav>
