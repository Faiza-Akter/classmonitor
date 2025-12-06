<x-guest-layout>
    {{-- Header --}}
    <div class="mb-6">
        <div class="inline-flex items-center rounded-full bg-bgSoft3 px-3 py-1 text-[11px] text-brandSlate">
            <span class="w-2 h-2 rounded-full bg-brandIndigo mr-2"></span>
            New to ClassMonitor?
        </div>
        <h1 class="mt-4 text-2xl font-semibold text-brandSlate">
            Create your account
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Choose your role so we can personalize your dashboard.
        </p>
    </div>

    {{-- Form card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-bgSoft3 px-5 py-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Role toggle --}}
            <div>
                <p class="text-xs font-medium text-gray-600 mb-2">I am a</p>
                <div class="grid grid-cols-2 gap-2">
                    <label class="group cursor-pointer">
                        <input type="radio" name="role" value="student"
                               class="peer hidden"
                               {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                        <div
                            class="rounded-2xl border border-bgSoft3 bg-bgSoft px-3 py-2 text-center text-xs
                                   peer-checked:border-brandIndigo peer-checked:bg-brandIndigo/10
                                   peer-checked:text-brandIndigo transition">
                            Student
                        </div>
                    </label>

                    <label class="group cursor-pointer">
                        <input type="radio" name="role" value="teacher"
                               class="peer hidden"
                               {{ old('role') === 'teacher' ? 'checked' : '' }}>
                        <div
                            class="rounded-2xl border border-bgSoft3 bg-bgSoft px-3 py-2 text-center text-xs
                                   peer-checked:border-brandEmerald peer-checked:bg-brandEmerald/10
                                   peer-checked:text-brandEmerald transition">
                            Teacher
                        </div>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('role')" class="mt-1 text-[11px]" />
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-medium text-gray-600 mb-1">
                    Full name
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full rounded-2xl border border-bgSoft3 bg-bgSoft px-4 py-2.5 text-sm
                              focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-[11px]" />
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-medium text-gray-600 mb-1">
                    Email address
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-2xl border border-bgSoft3 bg-bgSoft px-4 py-2.5 text-sm
                              focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-[11px]" />
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-xs font-medium text-gray-600 mb-1">
                    Password
                </label>
                <input id="password" type="password" name="password" required
                       class="w-full rounded-2xl border border-bgSoft3 bg-bgSoft px-4 py-2.5 text-sm
                              focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-[11px]" />
            </div>

            {{-- Confirm --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-gray-600 mb-1">
                    Confirm password
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full rounded-2xl border border-bgSoft3 bg-bgSoft px-4 py-2.5 text-sm
                              focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full mt-1 inline-flex justify-center items-center rounded-2xl
                           bg-brandAmber text-brandSlate text-sm font-semibold px-4 py-2.5
                           shadow-md hover:bg-brandWarning transition-transform transform hover:-translate-y-0.5">
                Create account
            </button>
        </form>
    </div>

    {{-- Switch --}}
    <p class="mt-4 text-xs text-gray-500 text-center">
        Already have an account?
        <a href="{{ route('login') }}" class="text-brandIndigo font-medium hover:underline">
            Log in instead
        </a>
    </p>
</x-guest-layout>
