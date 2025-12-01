<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        // Middleware now handled in the route definitions.
    }

    /**
     * Display attendance form for a subject.
     */
    public function create(Subject $subject)
    {
        $teacher = auth()->user();
        
        // Check if teacher is assigned to this subject
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $students = $subject->students()->orderBy('name')->get();
        $today = now()->format('Y-m-d');
        
        // Get today's attendance as Eloquent models keyed by student_id
        $todayAttendance = Attendance::query()
            ->where('subject_id', $subject->id)
            ->where('attendance_date', $today)
            ->get()
            ->mapWithKeys(function ($attendance) {
                return [$attendance->student_id => $attendance];
            });

        return view('teacher.attendance.create', compact('subject', 'students', 'today', 'todayAttendance'));
    }

    /**
     * Store attendance.
     */
    public function store(Request $request, Subject $subject)
    {
        $teacher = auth()->user();
        
        // Check if teacher is assigned to this subject
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.notes' => 'nullable|string',
        ]);

        $date = $validated['attendance_date'];

        // Delete existing attendance for this date and subject
        Attendance::where('subject_id', $subject->id)
            ->where('attendance_date', $date)
            ->delete();

        // Create new attendance records
        foreach ($validated['attendances'] as $attendanceData) {
            Attendance::create([
                'student_id' => $attendanceData['student_id'],
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'attendance_date' => $date,
                'status' => $attendanceData['status'],
                'notes' => $attendanceData['notes'] ?? null,
            ]);
        }

        return redirect()->route('teacher.attendance.create', $subject)
            ->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Show attendance history for a subject.
     */
    public function history(Subject $subject)
    {
        $teacher = auth()->user();
        
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $attendances = Attendance::where('subject_id', $subject->id)
            ->with('student')
            ->orderBy('attendance_date', 'desc')
            ->orderBy('student_id')
            ->paginate(20);

        return view('teacher.attendance.history', compact('subject', 'attendances'));
    }
}

