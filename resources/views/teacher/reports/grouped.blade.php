@extends('layouts.app')

@section('title', 'Grouped Report - ' . $subject->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Grouped Report</h1>
            <p class="mt-2 text-sm text-gray-700">Subject: <strong>{{ $subject->name }}</strong></p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('teacher.reports.grouped', $subject) }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Filter by Date</label>
                    <input type="date" name="date" id="date" value="{{ $dateFilter }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Filter by Month</label>
                    <input type="month" name="month" id="month" value="{{ $monthFilter }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Apply Filter
                    </button>
                    <a href="{{ route('teacher.reports.grouped', $subject) }}" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Clear Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Student</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Present</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Absent</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Late</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Excused</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Present %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($studentsData as $data)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $data['student']->name }}<br>
                                    <span class="text-gray-500 text-xs">({{ $data['student']->student_id }})</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $data['stats']['total'] }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $data['stats']['present'] }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $data['stats']['absent'] }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $data['stats']['late'] }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $data['stats']['excused'] }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $data['stats']['present_percentage'] >= 75 ? 'bg-green-100 text-green-800' : ($data['stats']['present_percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $data['stats']['present_percentage'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-sm text-gray-500">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

