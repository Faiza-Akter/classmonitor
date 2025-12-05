@extends('layouts.guest')

@section('title', 'Create account')
@section('heading', 'Create your ClassMonitor account')
@section('subheading', 'Choose your role to get a personalized experience.')

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Role toggle --}}
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-600">I am a</span>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-2">
            <label class="group cursor-pointer">
                <input type="radio" name="role" value="student"
                       class="peer hidden"
                       {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                <div class="rounded-2xl border border-gray-200 bg-white py-2.5 px-3 text-center text-xs
                            peer-checked:border-brandIndigo peer-checked:bg-brandIndigo/10
                            peer-checked:text-brandIndigo transition">
                    Student
                </div>
            </label>
            <label class="group cursor-pointer">
                <input type="radio" name="role" value="teacher"
                       class="peer hidden"
                       {{ old('role') === 'teacher' ? 'checked' : '' }}>
                <div class="rounded-2xl border border-gray-200 bg-white py-2.5 px-3 text-center text-xs
                            peer-checked:border-brandEmerald peer-checked:bg-brandEmerald/10
                            peer-checked:text-brandEmerald transition">
                    Teacher
                </div>
            </label>
        </div>
        @error('role')
        <p class="mt-1 text-xs text-brandError">{{ $message }}</p>
        @enderror

        {{-- Name --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1" for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                          focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
            @error('name')
            <p class="mt-1 text-xs text-brandError">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                          focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
            @error('email')
            <p class="mt-1 text-xs text-brandError">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1" for="password">Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                          focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
            @error('password')
            <p class="mt-1 text-xs text-brandError">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                          focus:border-brandIndigo focus:ring-1 focus:ring-brandIndigo/60">
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full mt-2 inline-flex justify-center items-center rounded-2xl bg-brandIndigo text-white text-sm font-medium
                       px-4 py-2.5 shadow-md hover:bg-brandIndigoLight transition-transform transform hover:-translate-y-0.5">
            Create account
        </button>

        {{-- Switch to login --}}
        <p class="mt-4 text-xs text-gray-500 text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brandIndigo font-medium hover:underline">Log in</a>
        </p>
    </form>
</x-guest-layout>
