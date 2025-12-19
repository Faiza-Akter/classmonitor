@extends('layouts.app')

@section('content')
@php
  $cmBlue   = '#2463EB';
  $cmGreen  = '#8BDE63';
  $cmYellow = '#EDB70A';
  $cmRed    = '#EF4444';
@endphp

{{-- Main wrapper --}}
<div class="min-h-[calc(100vh-88px)]" style="background: {{ $cmGreen }};">

  {{-- Navbar bottom border (same as other pages) --}}
  <div class="h-[6px] w-full"
       style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

  <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Card --}}
    <div class="rounded-3xl bg-white border border-white/60 shadow-[0_18px_45px_rgba(15,23,42,0.14)] overflow-hidden">

      {{-- Header (NO background, centered text) --}}
      <div class="px-6 pt-8 pb-6 text-center">
        <p class="text-xs font-extrabold tracking-widest uppercase text-slate-500">
          Attendance
        </p>

        <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900">
          Join Session
        </h1>

        <p class="mt-2 text-sm text-slate-600 max-w-md mx-auto">
          Enter the session code your teacher provided.
        </p>
      </div>

      {{-- Form --}}
      <div class="px-6 pb-8">
        <form method="POST" action="{{ route('attendance.join') }}" class="space-y-5">
          @csrf

          <div>
            <label class="text-sm font-extrabold text-slate-700">
              Session Code
            </label>

            <input
              name="code"
              value="{{ old('code') }}"
              placeholder="e.g. A1B2C3"
              autocomplete="off"
              class="mt-2 w-full rounded-2xl border px-4 py-3 text-center text-slate-900 font-extrabold tracking-[0.35em]
                     focus:outline-none focus:ring-4"
              style="
                border-color: rgba(36,99,235,.22);
                background: rgba(36,99,235,.04);
                --tw-ring-color: rgba(36,99,235,.25);
              "
            />

            @error('code')
              <p class="mt-2 text-sm font-semibold" style="color: {{ $cmRed }};">
                {{ $message }}
              </p>
            @enderror
          </div>

          <button type="submit"
                  class="w-full rounded-2xl py-3 font-extrabold text-white
                         transition shadow-[0_12px_26px_rgba(36,99,235,0.28)]
                         hover:shadow-[0_16px_34px_rgba(36,99,235,0.36)]
                         hover:-translate-y-[1px]"
                  style="background: {{ $cmBlue }};">
            Mark Attendance
          </button>

          {{-- Helper tip --}}
          <div class="pt-1 flex justify-center gap-2 text-xs text-slate-600">
            <span class="mt-[3px] w-2 h-2 rounded-full"
                  style="background: {{ $cmYellow }};"></span>
            <span>
              If QR doesn’t work, just type the code.
            </span>
          </div>
        </form>
      </div>

    </div>

  </div>
</div>
@endsection
