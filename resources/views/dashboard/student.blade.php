@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

<div class="grid grid-cols-12 gap-6">

    {{-- Welcome Card --}}
    <section class="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-sm p-6 flex">
        <div class="flex-1">
            <h2 class="text-xl font-semibold">Hello, Grace! 👋</h2>
            <p class="text-sm text-gray-500 mt-2">
                You have <span class="text-brandIndigo font-semibold">3 new tasks</span> today.
            </p>

            <button class="mt-4 px-4 py-2 bg-brandIndigo text-white text-sm rounded-2xl shadow">
                Review tasks
            </button>
        </div>

        <div class="hidden md:flex items-center justify-center w-40">
            <div class="w-28 h-28 bg-brandOffWhite rounded-3xl flex items-center justify-center text-4xl">
                💻
            </div>
        </div>
    </section>


    {{-- Calendar --}}
    <section class="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold">Calendar</h3>
            <span class="text-xs text-gray-500">Today</span>
        </div>

        <div class="space-y-3 text-xs">

            {{-- Event --}}
            <div class="rounded-2xl border border-brandSky/40 bg-brandSky/10 p-3">
                <div class="flex justify-between text-sm">
                    <span class="font-semibold text-brandSlate">Electronics Lecture</span>
                    <span class="text-gray-500">09:45–10:30</span>
                </div>
                <p class="text-gray-500 text-[11px]">Room 302 · Quiz scheduled</p>
            </div>

            <div class="rounded-2xl border bg-brandOffWhite p-3 flex justify-between">
                <span class="font-semibold">Robotics Lesson</span>
                <span class="text-gray-500">11:00–11:45</span>
            </div>

            <div class="rounded-2xl border bg-brandOffWhite p-3 flex justify-between">
                <span class="font-semibold">C++ Lesson</span>
                <span class="text-gray-500">13:45–15:20</span>
            </div>

        </div>

    </section>


    {{-- Performance --}}
    <section class="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-sm p-6">

        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold">Performance</h3>
            <span class="text-xs text-gray-500">December</span>
        </div>

        <div class="flex items-end space-x-3 mt-6">

            @foreach ([
                'Algorithms' => 90,
                'OOP' => 75,
                'DB' => 85,
                'Web' => 78,
                'Mobile' => 70,
                'ML' => 92,
            ] as $label => $value)

                <div class="flex flex-col items-center flex-1">
                    <div class="h-24 bg-brandIndigo/10 w-8 rounded-full flex items-end">
                        <div class="w-full bg-brandIndigo rounded-full" style="height: {{ $value }}%;"></div>
                    </div>
                    <span class="mt-2 text-[11px] text-gray-500">{{ $label }}</span>
                </div>

            @endforeach

        </div>

    </section>


    {{-- Attendance Summary --}}
    <section class="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-sm p-6">

        <h3 class="text-sm font-semibold mb-4">My Attendance</h3>

        <div class="flex justify-between">

            <div class="flex flex-col items-center">
                <div class="w-20 h-20 border-8 border-brandEmerald/30 rounded-full flex items-center justify-center text-brandEmerald font-semibold">
                    92%
                </div>
                <span class="mt-2 text-[11px] text-gray-500">Overall</span>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-20 h-20 border-8 border-brandSky/30 rounded-full flex items-center justify-center text-brandSky font-semibold">
                    83%
                </div>
                <span class="mt-2 text-[11px] text-gray-500">Participation</span>
            </div>

        </div>

    </section>

</div>

@endsection
