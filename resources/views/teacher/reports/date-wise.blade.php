@extends('layouts.app')

@section('title', 'Date-wise Report - ' . $subject->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Date-wise Report</h1>
            <p class="mt-2 text-sm text-gray-700">Subject: <strong>{{ $subject->name }}</strong></p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('teacher.reports.date-wise', $subject) }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <label for="date" class="block text-sm font-medium text-gray-700">Select Date</label>
                    <input type="date" name="date" id="date" value="{{ $dateFilter }}" required onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance for {{ $dateFilter }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Student</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Student ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($students as $student)
                            @php
                                $attendance = $attendanceMap->get($student->id);
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $student->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $student->student_id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @if($attendance)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $attendance->status == 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $attendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $attendance->status == 'excused' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Not Marked
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">{{ $attendance?->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-sm text-gray-500">No students enrolled in this subject.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

