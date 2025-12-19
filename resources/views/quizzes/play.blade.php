@extends('layouts.app')

@section('content')
  @php
    $cmBlue = '#2463EB';
    $cmGreen = '#8BDE63';
    $cmYellow = '#EDB70A';
  @endphp

  <div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-sm font-semibold text-slate-600">Quiz</p>
          <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
          <p class="mt-1 text-sm text-slate-600">Answer all questions and submit.</p>
        </div>

        @if(isset($remainingSeconds) && $remainingSeconds !== null)
          <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Time Left</p>
            <p id="timer" class="text-2xl font-extrabold" style="color: {{ $cmBlue }};">--:--</p>
          </div>
        @endif
      </div>

      @if($errors->any())
        <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="quizForm" class="mt-6 space-y-4" method="POST" action="{{ route('quizzes.submit', $quiz) }}">
        @csrf

        @foreach($quiz->questions as $q)
          @php
            $type = $q->type ?? 'mcq';
            $badge = strtoupper($type);
            $badgeBg = $type === 'short'
              ? 'rgba(36,99,235,.14)'
              : ($type === 'tf' ? 'rgba(139,222,99,.18)' : 'rgba(237,183,10,.18)');
            $badgeColor = $type === 'short'
              ? '#0b2a7a'
              : ($type === 'tf' ? '#0B1B0F' : '#3a2b00');
          @endphp

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <p class="font-extrabold break-words">{{ $q->text }}</p>
                <p class="text-sm text-slate-600 mt-1">Points: {{ $q->points }}</p>
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-lg"
                style="background: {{ $badgeBg }}; color: {{ $badgeColor }};">
                {{ $badge }}
              </span>
            </div>

            {{-- MCQ / TF --}}
            @if(in_array($type, ['mcq', 'tf']))
              <div class="mt-4 space-y-2">
                @foreach($q->options as $opt)
                  <label
                    class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 cursor-pointer hover:bg-white transition">
                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" required>
                    <span class="text-sm">{{ $opt->text }}</span>
                  </label>
                @endforeach
              </div>
            @endif

            {{-- SHORT ANSWER --}}
            @if($type === 'short')
              <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700">Your Answer</label>
                <textarea name="short[{{ $q->id }}]" rows="4" required
                  class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                  placeholder="Write your answer here...">{{ old("short.$q->id") }}</textarea>
                <p class="mt-2 text-xs text-slate-500">This question will be graded by the teacher.</p>
              </div>
            @endif
          </div>
        @endforeach

        <button type="submit" class="px-6 py-3 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
          style="background: {{ $cmBlue }};">
          Submit Quiz
        </button>
      </form>

    </div>
  </div>

  @if(isset($remainingSeconds) && $remainingSeconds !== null)
    <script>
      let remaining = Math.max(0, parseInt(@json($remainingSeconds), 10) || 0);
      let submitted = false;

      function fmt(sec) {
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
      }

      const timerEl = document.getElementById('timer');
      const form = document.getElementById('quizForm');

      function lockForm() {
        if (submitted) return;
        submitted = true;

        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
          btn.disabled = true;
          btn.textContent = 'Submitting...';
          btn.style.opacity = '0.8';
          btn.style.cursor = 'not-allowed';
        }
      }

      form.addEventListener('submit', () => lockForm());

      function tick() {
        if (timerEl) timerEl.textContent = fmt(Math.max(0, remaining));

        if (remaining <= 0) {
          lockForm();
          form.submit();
          return;
        }

        remaining -= 1;
        setTimeout(tick, 1000);
      }

      tick();
    </script>

  @endif
@endsection