@extends('layouts.app')

@section('content')
@php
    $cmBlue   = '#2463EB';
    $cmGreen  = '#8BDE63';
    $cmYellow = '#EDB70A';
@endphp

<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page header --}}
        <div class="mb-6">
            <p class="text-sm font-semibold text-slate-600">Account</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
                Profile Settings
            </h1>
            <p class="mt-1 text-sm text-slate-600">
                Update your information, password, and security settings.
            </p>
        </div>

        {{-- Force light styles even if dark mode class exists --}}
        <div class="space-y-5
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
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Profile Information</h2>
                        <p class="text-sm text-slate-600">Update your name and email address.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-slate-50">
                        <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                        Profile
                    </span>
                </div>

                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password --}}
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Update Password</h2>
                        <p class="text-sm text-slate-600">Use a strong password to keep your account secure.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-slate-50">
                        <span class="w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                        Security
                    </span>
                </div>

                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account --}}
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Delete Account</h2>
                        <p class="text-sm text-slate-600">
                            Permanently delete your account and associated data.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-slate-50">
                        <span class="w-2 h-2 rounded-full" style="background: {{ $cmGreen }};"></span>
                        Danger
                    </span>
                </div>

                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
