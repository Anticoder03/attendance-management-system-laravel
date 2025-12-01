@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Attendance Reports</h1>
            <p class="mt-2 text-sm text-gray-700">View detailed attendance reports for your subjects.</p>
        </div>
    </div>

    @if($subjects->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">No subjects assigned yet. Contact your administrator.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($subjects as $subject)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $subject->name }}</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <a href="{{ route('teacher.reports.individual', $subject) }}" class="relative rounded-lg border border-gray-300 bg-white px-4 py-3 shadow-sm hover:border-gray-400 text-center">
                                <p class="text-sm font-medium text-gray-900">Individual Report</p>
                                <p class="mt-1 text-xs text-gray-500">View by student</p>
                            </a>
                            <!-- <a href="{{ route('teacher.reports.grouped', $subject) }}" class="relative rounded-lg border border-gray-300 bg-white px-4 py-3 shadow-sm hover:border-gray-400 text-center">
                                <p class="text-sm font-medium text-gray-900">Grouped Report</p>
                                <p class="mt-1 text-xs text-gray-500">View all students</p>
                            </a>
                            <a href="{{ route('teacher.reports.date-wise', $subject) }}" class="relative rounded-lg border border-gray-300 bg-white px-4 py-3 shadow-sm hover:border-gray-400 text-center">
                                <p class="text-sm font-medium text-gray-900">Date-wise Report</p>
                                <p class="mt-1 text-xs text-gray-500">View by date</p>
                            </a>
                            <a href="{{ route('teacher.reports.month-wise', $subject) }}" class="relative rounded-lg border border-gray-300 bg-white px-4 py-3 shadow-sm hover:border-gray-400 text-center">
                                <p class="text-sm font-medium text-gray-900">Month-wise Report</p>
                                <p class="mt-1 text-xs text-gray-500">View by month</p>
                            </a> -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

