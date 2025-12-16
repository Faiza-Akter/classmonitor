@extends('layouts.app')

@section('content')
@php
    $cmBlue   = '#2463EB';
    $cmGreen  = '#8BDE63';
    $cmYellow = '#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-600">Student</p>
                <h1 class="mt-1 text-3xl font-extrabold">Attendance History</h1>
                <p class="mt-1 text-sm text-slate-600">Your previous attendance check-ins.</p>
            </div>

            <a href="{{ route('student.dashboard') }}"
               class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
                Back to Dashboard
            </a>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <p class="font-bold">Records</p>
            </div>

            @if($rows->count() === 0)
                <div class="p-6 text-slate-600">
                    No attendance records yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-5 py-3 font-bold">Date</th>
                                <th class="text-left px-5 py-3 font-bold">Teacher</th>
                                <th class="text-left px-5 py-3 font-bold">Session Code</th>
                                <th class="text-left px-5 py-3 font-bold">Checked In</th>
                                <th class="text-left px-5 py-3 font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($rows as $r)
                                @php
                                    $s = $r->session;
                                    $teacherName = $s?->teacher?->name ?? '—';
                                    $date = $r->checked_in_at ? $r->checked_in_at->format('d M, Y') : '—';
                                    $time = $r->checked_in_at ? $r->checked_in_at->format('h:i A') : '—';

                                    $isEnded = (bool) $s?->ended_at;
                                    $statusText = $isEnded ? 'Completed' : 'Active';
                                    $dot = $isEnded ? $cmYellow : $cmGreen;
                                @endphp
                                <tr>
                                    <td class="px-5 py-4">{{ $date }}</td>
                                    <td class="px-5 py-4 font-semibold">{{ $teacherName }}</td>
                                    <td class="px-5 py-4 font-mono font-bold">{{ $s?->session_code ?? '—' }}</td>
                                    <td class="px-5 py-4">{{ $time }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border border-slate-200 bg-white">
                                            <span class="w-2 h-2 rounded-full" style="background: {{ $dot }}"></span>
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-4 border-t border-slate-200">
                    {{ $rows->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
