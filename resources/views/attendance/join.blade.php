@extends('layouts.app')

@section('content')
@php $cmBlue = '#2463EB'; @endphp

<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
        <p class="text-sm font-semibold text-slate-600">Attendance</p>
        <h1 class="mt-1 text-2xl font-extrabold text-slate-900">Join Session</h1>
        <p class="mt-1 text-sm text-slate-600">Enter the session code your teacher provided.</p>

        <form method="POST" action="{{ route('attendance.join') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-bold text-slate-700">Session Code</label>
                <input name="code" value="{{ old('code') }}"
                       class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 font-semibold tracking-widest"
                       placeholder="e.g. A1B2C3" />
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full px-4 py-3 rounded-xl font-extrabold text-white shadow-sm hover:shadow-md transition"
                    style="background: {{ $cmBlue }};">
                Mark Attendance
            </button>
        </form>
    </div>
</div>
@endsection
