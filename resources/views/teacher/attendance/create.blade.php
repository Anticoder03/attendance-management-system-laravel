@extends('layouts.app')

@section('title', 'Take Attendance - ' . $subject->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Take Attendance</h1>
            <p class="mt-2 text-sm text-gray-700">Subject: <strong>{{ $subject->name }}</strong></p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Back to Dashboard
            </a>
        </div>
    </div>

    <form action="{{ route('teacher.attendance.store', $subject) }}" method="POST">
        @csrf
        
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-4">
                    <label for="attendance_date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="attendance_date" id="attendance_date" required value="{{ old('attendance_date', $today) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Students</h3>
                
                @if($students->isEmpty())
                    <p class="text-sm text-gray-500">No students enrolled in this subject.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Student Name</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Student ID</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($students as $student)

                                    @php
                                        $existingAttendance = $todayAttendance->get($student->id);
                                        $status = $existingAttendance?->status ?? "";
                                        $selectedStatus = old("attendances.{$loop->index}.status", $status);
                                        $notes = old("attendances.{$loop->index}.notes", $existingAttendance?->notes);
                                    @endphp

                                    <tr>
                                        <td class="py-4 pl-4 pr-3 text-sm text-gray-900 font-medium sm:pl-6">
                                            {{ $student->name }}
                                            <input type="hidden" name="attendances[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                        </td>

                                        <td class="px-3 py-4 text-sm text-gray-500">
                                            {{ $student->student_id }}
                                        </td>

                                        <td class="px-3 py-4 text-sm">
                                            <select name="attendances[{{ $loop->index }}][status]" class="border rounded-md px-2 py-1">
                                                <option value="present" {{ $selectedStatus == 'present' ? 'selected' : '' }}>Present</option>
                                                <option value="absent" {{ $selectedStatus == 'absent' ? 'selected' : '' }}>Absent</option>
                                                <option value="late" {{ $selectedStatus == 'late' ? 'selected' : '' }}>Late</option>
                                            </select>
                                        </td>

                                        <td class="px-3 py-4 text-sm">
                                            <input 
                                                type="text" 
                                                name="attendances[{{ $loop->index }}][notes]"
                                                value="{{ $notes }}"
                                                placeholder="Optional notes"
                                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        @if(!$students->isEmpty())
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('teacher.dashboard') }}" class="bg-white border border-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50">Cancel</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm shadow-sm">
                    Save Attendance
                </button>
            </div>
        @endif
    </form>
</div>
@endsection
