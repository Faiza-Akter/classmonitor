<nav x-data="{ open: false }" class="w-full">
    @php
        $cmBlue   = '#2463EB';
        $cmGreen  = '#8BDE63';
        $cmYellow = '#EDB70A';

        $user = Auth::user();
        $role = $user?->role ?? null;

        $dashUrl = $role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard');

        $attendanceUrl = $role === 'teacher'
            ? route('attendance.index')
            : route('attendance.join.form');

        $quizzesUrl = $role === 'teacher'
            ? route('quizzes.index')
            : route('student.quizzes.history');

        $roleLabel = $role ? ucfirst($role) : 'Account';

        $isDash = request()->routeIs('teacher.dashboard') || request()->routeIs('student.dashboard') || request()->routeIs('dashboard');
        $isAttendance = $role === 'teacher'
            ? request()->routeIs('attendance.*') || request()->is('attendance*')
            : request()->routeIs('attendance.join.*') || request()->routeIs('student.attendance.*') || request()->is('attendance/join') || request()->is('student/attendance');
        $isQuizzes = $role === 'teacher'
            ? request()->routeIs('quizzes.*') || request()->is('quizzes*')
            : request()->routeIs('student.quizzes.*') || request()->is('student/quizzes*') || request()->routeIs('quizzes.play') || request()->routeIs('quizzes.result');

        $cmLogo = asset('images/cm-logo.png');

        $activeTab = $isAttendance ? 'attendance' : ($isQuizzes ? 'quizzes' : 'dashboard');
    @endphp

    <div class="sticky top-0 z-50">
        {{-- Top accent strip --}}
        <div class="h-[6px] w-full"
             style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

        <div class="bg-white/85 backdrop-blur border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between gap-4">

                    {{-- Brand --}}
                    <a href="{{ $dashUrl }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-2xl border border-slate-200 bg-white shadow-sm grid place-items-center overflow-hidden transition group-hover:shadow-md">
                            <img src="{{ $cmLogo }}" alt="ClassMonitor Logo" class="w-8 h-8 object-contain">
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm font-extrabold text-slate-900 tracking-tight">ClassMonitor</div>
                            <div class="text-[11px] text-slate-500 -mt-0.5">{{ $roleLabel }}</div>
                        </div>
                    </a>

                    {{-- Center tabs (desktop) --}}
                    <div class="hidden sm:block">
                        <div class="relative cm-tabs" data-active="{{ $activeTab }}">
                            <div class="flex items-center gap-1">
                                <a href="{{ $dashUrl }}"
                                   data-tab="dashboard"
                                   class="cm-tab {{ $isDash ? 'is-active' : '' }}">
                                    Dashboard
                                </a>

                                <a href="{{ $attendanceUrl }}"
                                   data-tab="attendance"
                                   class="cm-tab {{ $isAttendance ? 'is-active' : '' }}">
                                    <span class="cm-dot" style="background: {{ $cmGreen }};"></span>
                                    Attendance
                                </a>

                                <a href="{{ $quizzesUrl }}"
                                   data-tab="quizzes"
                                   class="cm-tab {{ $isQuizzes ? 'is-active' : '' }}">
                                    <span class="cm-dot" style="background: {{ $cmYellow }};"></span>
                                    Quizzes
                                </a>

                                @if($role === 'student')
                                    <a href="{{ route('student.attendance.history') }}" class="cm-tab">History</a>
                                @endif
                            </div>

                            {{-- Active pill --}}
                            <span class="cm-underline" aria-hidden="true"></span>
                        </div>
                    </div>

                    {{-- Right (desktop) --}}
                    <div class="hidden sm:flex items-center gap-5">

                        {{-- Online indicator (no card) --}}
                        <div class="hidden md:flex items-center gap-2">
                            <span class="cm-pulse">
                                <span class="cm-pulse-dot" style="background: {{ $cmGreen }};"></span>
                            </span>
                            <span class="text-xs font-extrabold text-slate-600">Online</span>
                        </div>

                        {{-- User dropdown (no card) --}}
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger">
                                <button class="cm-user inline-flex items-center gap-3 focus:outline-none">
                                    <div class="w-10 h-10 rounded-2xl grid place-items-center text-white font-extrabold shadow-sm"
                                         style="background: {{ $cmBlue }};">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div class="text-left leading-tight">
                                        <div class="text-sm font-extrabold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $roleLabel }}</div>
                                    </div>

                                    <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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

                    {{-- Mobile button --}}
                    <div class="flex items-center sm:hidden">
                        <button @click="open = !open"
                                class="inline-flex items-center justify-center p-2 rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:shadow-md transition"
                                aria-label="Open Menu">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            {{-- Mobile drawer --}}
            <div x-show="open" x-transition.opacity class="sm:hidden">
                <div class="px-4 pb-4 pt-3 bg-white border-t border-slate-200 space-y-2">
                    <a href="{{ $dashUrl }}" class="cm-mobile-link">Dashboard</a>

                    <a href="{{ $attendanceUrl }}" class="cm-mobile-link">
                        <span class="inline-block w-2 h-2 rounded-full mr-2 align-middle" style="background:{{ $cmGreen }};"></span>
                        Attendance
                    </a>

                    @if($role === 'student')
                        <a href="{{ route('student.attendance.history') }}" class="cm-mobile-link">Attendance History</a>
                    @endif

                    <a href="{{ $quizzesUrl }}" class="cm-mobile-link">
                        <span class="inline-block w-2 h-2 rounded-full mr-2 align-middle" style="background:{{ $cmYellow }};"></span>
                        Quizzes
                    </a>

                    <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-sm font-extrabold text-slate-900">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">{{ $roleLabel }}</p>

                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('profile.edit') }}"
                               class="flex-1 text-center px-4 py-2 rounded-full font-extrabold text-white"
                               style="background:{{ $cmBlue }};">
                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full px-4 py-2 rounded-full font-extrabold border border-slate-200 bg-white text-slate-900">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .cm-tabs{
                    padding: 4px;
                    border-radius: 9999px;
                    border: 1px solid rgba(226,232,240,1);
                    background: linear-gradient(180deg, rgba(248,250,252,1), rgba(255,255,255,1));
                    box-shadow: 0 10px 28px rgba(15,23,42,.06);
                    position: relative;
                }

                .cm-tab{
                    position: relative;
                    z-index: 2;
                    display:inline-flex;
                    align-items:center;
                    gap:.55rem;
                    padding: .55rem 1rem;
                    border-radius: 9999px;
                    font-weight: 900;
                    font-size: .875rem;
                    color: #334155;
                    transition: color .2s ease, transform .2s ease;
                    user-select: none;
                }
                .cm-tab:hover{ transform: translateY(-1px); color:#0f172a; }

                .cm-dot{ width: 8px; height: 8px; border-radius: 999px; display:inline-block; }

                /* ✅ Pill is smaller than the tab (prevents overflow always) */
                .cm-underline{
                    position:absolute;
                    top: 6px;
                    left: 0; /* IMPORTANT: JS handles the +6 inset */
                    height: calc(100% - 12px);
                    width: 120px;
                    border-radius: 9999px;
                    background: rgba(36,99,235,.16);
                    border: 1px solid rgba(36,99,235,.20);
                    box-shadow: 0 10px 26px rgba(36,99,235,.14);
                    transition: transform .28s ease, width .28s ease, background .28s ease, border-color .28s ease, box-shadow .28s ease;
                    z-index: 1;
                }

                .cm-user{ padding: 4px; border-radius: 9999px; transition: transform .15s ease, background .2s ease; }
                .cm-user:hover{ background: rgba(248,250,252,1); transform: translateY(-1px); }

                .cm-pulse{ position: relative; width: 12px; height: 12px; display:inline-grid; place-items:center; }
                .cm-pulse-dot{ width: 8px; height: 8px; border-radius: 999px; }
                .cm-pulse::after{
                    content:"";
                    position:absolute;
                    inset: -6px;
                    border-radius: 999px;
                    border: 2px solid rgba(139,222,99,.35);
                    animation: cmPulse 1.6s ease-out infinite;
                }
                @keyframes cmPulse{
                    0%{ transform: scale(.65); opacity:.9; }
                    100%{ transform: scale(1.25); opacity:0; }
                }

                .cm-mobile-link{
                    display:block;
                    padding: .9rem 1rem;
                    border-radius: 1rem;
                    border: 1px solid rgba(226,232,240,1);
                    background: #fff;
                    font-weight: 900;
                    color: #0f172a;
                }
            </style>

            <script>
                (function(){
                    const root = document.querySelector('.cm-tabs');
                    if(!root) return;

                    const underline = root.querySelector('.cm-underline');
                    const active = root.querySelector('.cm-tab.is-active') || root.querySelector('[data-tab="dashboard"]');
                    if(!underline || !active) return;

                    const INSET = 6;      // how much we want to keep inside on left/right
                    const SHRINK = INSET * 2; // subtract from width

                    function moveTo(el){
                        const r = el.getBoundingClientRect();
                        const pr = root.getBoundingClientRect();

                        // ✅ start at tab-left, then push inside by +6
                        const x = (r.left - pr.left) + INSET;

                        // ✅ make pill smaller than tab by -12 (6 left + 6 right)
                        const w = Math.max(0, r.width - SHRINK);

                        underline.style.width = w + 'px';
                        underline.style.transform = `translateX(${x}px)`;

                        const tab = el.getAttribute('data-tab');
                        if(tab === 'attendance'){
                            underline.style.background = 'rgba(139,222,99,.20)';
                            underline.style.borderColor = 'rgba(139,222,99,.28)';
                            underline.style.boxShadow = '0 10px 26px rgba(139,222,99,.18)';
                        }else if(tab === 'quizzes'){
                            underline.style.background = 'rgba(237,183,10,.20)';
                            underline.style.borderColor = 'rgba(237,183,10,.28)';
                            underline.style.boxShadow = '0 10px 26px rgba(237,183,10,.18)';
                        }else{
                            underline.style.background = 'rgba(36,99,235,.18)';
                            underline.style.borderColor = 'rgba(36,99,235,.24)';
                            underline.style.boxShadow = '0 10px 26px rgba(36,99,235,.18)';
                        }
                    }

                    moveTo(active);

                    window.addEventListener('resize', () => {
                        moveTo(root.querySelector('.cm-tab.is-active') || active);
                    });

                    root.querySelectorAll('.cm-tab[data-tab]').forEach(tab=>{
                        tab.addEventListener('mouseenter', ()=> moveTo(tab));
                        tab.addEventListener('mouseleave', ()=> moveTo(root.querySelector('.cm-tab.is-active') || active));
                    });
                })();
            </script>
        </div>
    </div>
</nav>
