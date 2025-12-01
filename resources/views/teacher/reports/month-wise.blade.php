@extends('layouts.app')

@section('title', 'Month-wise Report - ' . $subject->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Month-wise Report</h1>
            <p class="mt-2 text-sm text-gray-700">Subject: <strong>{{ $subject->name }}</strong></p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('teacher.reports.month-wise', $subject) }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <label for="month" class="block text-sm font-medium text-gray-700">Select Month</label>
                    <input type="month" name="month" id="month" value="{{ $monthFilter }}" required onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </form>
        </div>
    </div>

    @foreach($studentsData as $data)
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $data['student']->name }}</h3>
                        <p class="text-sm text-gray-500">Student ID: {{ $data['student']->student_id }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Days: <span class="font-medium text-gray-900">{{ $data['stats']['total'] }}</span></p>
                        <p class="text-sm text-gray-500">Present: <span class="font-medium text-green-600">{{ $data['stats']['present_percentage'] }}%</span></p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($data['dailyBreakdown'] as $date => $status)
                                    <th scope="col" class="px-2 py-2 text-center text-xs font-semibold text-gray-900">{{ date('d', strtotime($date)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr>
                                @foreach($data['dailyBreakdown'] as $date => $status)
                                    <td class="px-2 py-2 text-center">
                                        @if($status == 'present')
                                            <span class="inline-block w-4 h-4 bg-green-500 rounded-full" title="Present"></span>
                                        @elseif($status == 'late')
                                            <span class="inline-block w-4 h-4 bg-yellow-500 rounded-full" title="Late"></span>
                                        @elseif($status == 'excused')
                                            <span class="inline-block w-4 h-4 bg-blue-500 rounded-full" title="Excused"></span>
                                        @else
                                            <span class="inline-block w-4 h-4 bg-red-500 rounded-full" title="Absent"></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-center gap-6 text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 bg-green-500 rounded-full"></span>
                        <span>Present</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 bg-yellow-500 rounded-full"></span>
                        <span>Late</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 bg-blue-500 rounded-full"></span>
                        <span>Excused</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 bg-red-500 rounded-full"></span>
                        <span>Absent</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if(empty($studentsData))
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <p class="text-center text-sm text-gray-500">No students enrolled in this subject.</p>
            </div>
        </div>
    @endif
</div>
@endsection

