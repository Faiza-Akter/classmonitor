@extends('layouts.app')

@section('content')
@php
    $cmBlue = '#2463EB';
    $cmGreen = '#8BDE63';
    $cmYellow = '#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] text-slate-900"
     style="background: linear-gradient(135deg, rgba(36,99,235,.08) 0%, #ffffff 55%, rgba(139,222,99,.10) 100%);">

    {{-- Top accent strip --}}
    <div class="h-[6px] w-full"
         style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        {{-- Header (gradient bg, centered text) --}}
        <section class="cm-animate-in rounded-3xl overflow-hidden shadow-[0_18px_45px_rgba(15,23,42,0.12)]"
                 style="background: linear-gradient(110deg, rgba(157,183,255,.9) 0%, rgba(189,240,178,.9) 48%, rgba(255,224,138,.9) 100%);">
            <div class="p-2 sm:p-4 text-center">
                <p class="text-xs font-extrabold tracking-widest uppercase text-slate-800">Quizzes</p>

                <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
                    Create <span class="text-[#2463EB]">Quiz</span>
                </h1>

                <p class="mt-2 text-sm sm:text-base text-slate-800">
                    Add a title and optional duration. You can manage questions after creating.
                </p>
            </div>
        </section>

        {{-- Errors --}}
        @if($errors->any())
            <div class="mt-5 rounded-2xl border px-5 py-4 cm-reveal"
                 style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.12);">
                <p class="font-extrabold text-red-900 mb-2">Please fix the following:</p>
                <ul class="list-disc pl-5 text-sm font-semibold text-red-900">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card (cmBlue background) --}}
        <form method="POST" action="{{ route('quizzes.store') }}"
              class="mt-6 cm-panel cm-animate-in"
              style="background: {{ $cmBlue }};">
            @csrf

            <div class="cm-panel-body">
                {{-- Title --}}
                <label class="block text-sm font-extrabold text-white">Title</label>
                <input name="title" value="{{ old('title') }}" required
                       class="cm-input mt-2"
                       placeholder="e.g. SWE Batch 5th Quiz 1">

                {{-- Duration --}}
                <div class="mt-5">
                    <label class="block text-sm font-extrabold text-white">
                        Duration (minutes)
                        <span class="text-white/70 font-semibold">(optional)</span>
                    </label>
                    <input name="duration" type="number" min="1" max="300" value="{{ old('duration') }}"
                           class="cm-input mt-2"
                           placeholder="e.g. 10">
                    <p class="mt-2 text-xs text-white/70 font-semibold">
                        Tip: Keep it between 5–30 minutes for quick in-class quizzes.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:items-center">
                    {{-- Create button: yellow bg, WHITE text --}}
                    <button type="submit" class="cm-btn-solid w-full sm:w-auto"
                            style="--btn: {{ $cmYellow }}; --btn-text:#FFFFFF;">
                        Create Quiz
                    </button>

                    {{-- Back button: WHITE bg + GREEN outline --}}
                    <a href="{{ route('quizzes.index') }}"
                       class="cm-btn-outline w-full sm:w-auto"
                       style="--btn: {{ $cmGreen }}; --btn-text: {{ $cmGreen }}; --btn-bg:#FFFFFF;">
                        Back
                    </a>
                </div>
            </div>
        </form>

        {{-- Styles --}}
        <style>
            .cm-animate-in{
                opacity:0;
                transform: translateY(10px);
                animation: cmIn .55s ease forwards;
            }
            @keyframes cmIn{ to{ opacity:1; transform:none; } }

            .cm-panel{
                border: 1px solid rgba(226,232,240,1);
                border-radius: 1.5rem;
                box-shadow: 0 10px 30px rgba(15,23,42,.06);
                overflow:hidden;
            }
            .cm-panel-body{ padding: 1.25rem; }
            @media (min-width: 640px){ .cm-panel-body{ padding: 1.5rem; } }

            .cm-input{
                width:100%;
                border-radius: 1rem;
                border: 1px solid rgba(226,232,240,1);
                padding: .85rem 1rem;
                font-weight: 600;
                color: rgb(15 23 42);
                background: rgba(255,255,255,.95);
                transition: box-shadow .2s ease, border-color .2s ease, transform .15s ease;
                outline: none;
            }
            .cm-input::placeholder{ color: rgba(100,116,139,.75); font-weight:600; }
            .cm-input:focus{
                border-color: rgba(255,255,255,.9);
                box-shadow: 0 0 0 4px rgba(255,255,255,.22);
                transform: translateY(-1px);
            }

            .cm-btn-solid{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding: .72rem 1.1rem;
                border-radius: 9999px;
                font-weight: 950;
                background: var(--btn);
                color: var(--btn-text, #111827);
                box-shadow: 0 6px 16px rgba(15,23,42,.12);
                transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
                white-space: nowrap;
            }
            .cm-btn-solid:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 26px rgba(15,23,42,.18);
                filter: brightness(1.02);
            }

            .cm-btn-outline{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding: .72rem 1.1rem;
                border-radius: 9999px;
                border: 2px solid var(--btn);
                color: var(--btn-text, var(--btn));
                font-weight: 950;
                background: var(--btn-bg, transparent);
                box-shadow: 0 6px 16px rgba(15,23,42,.10);
                transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
                white-space: nowrap;
            }
            .cm-btn-outline:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 26px rgba(15,23,42,.14);
                background: rgba(255,255,255,.10);
            }

            @media (prefers-reduced-motion: reduce){
                .cm-animate-in{ animation:none !important; opacity:1 !important; transform:none !important; }
                .cm-btn-solid, .cm-btn-outline, .cm-input{ transition:none !important; }
            }
        </style>

    </div>
</div>
@endsection
