<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        // Middleware now handled in the route definitions.
    }

    /**
     * Display reports index.
     */
    public function index()
    {
        $teacher = auth()->user();
        $subjects = $teacher->subjects;
        
        return view('teacher.reports.index', compact('subjects'));
    }

    /**
     * Display individual student report.
     */
    public function individual(Request $request, Subject $subject)
    {
        $teacher = auth()->user();
        
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $students = $subject->students()->orderBy('name')->get();
        $student = null;
        $attendances = collect();
        $stats = null;

        if ($request->has('student_id')) {
            $request->validate([
                'student_id' => 'required|exists:students,id',
            ]);

            $student = Student::findOrFail($request->student_id);
            
            // Verify student is enrolled in this subject
            if (!$student->subjects->contains($subject)) {
                abort(403, 'Student is not enrolled in this subject.');
            }

            $attendances = Attendance::where('subject_id', $subject->id)
                ->where('student_id', $student->id)
                ->orderBy('attendance_date', 'desc')
                ->get();

            $stats = $this->calculateStudentStats($attendances);
        }

        return view('teacher.reports.individual', compact('subject', 'students', 'student', 'attendances', 'stats'));
    }

    /**
     * Display grouped report (all students).
     */
    public function grouped(Subject $subject, Request $request)
    {
        $teacher = auth()->user();
        
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $students = $subject->students()->orderBy('name')->get();
        
        $dateFilter = $request->get('date');
        $monthFilter = $request->get('month');
        
        $studentsData = [];
        
        foreach ($students as $student) {
            $query = Attendance::where('subject_id', $subject->id)
                ->where('student_id', $student->id);
            
            if ($dateFilter) {
                $query->where('attendance_date', $dateFilter);
            } elseif ($monthFilter) {
                $query->whereMonth('attendance_date', date('m', strtotime($monthFilter)))
                    ->whereYear('attendance_date', date('Y', strtotime($monthFilter)));
            }
            
            $attendances = $query->get();
            $stats = $this->calculateStudentStats($attendances);
            
            $studentsData[] = [
                'student' => $student,
                'attendances' => $attendances,
                'stats' => $stats,
            ];
        }

        return view('teacher.reports.grouped', compact('subject', 'studentsData', 'dateFilter', 'monthFilter'));
    }

    /**
     * Display date-wise report.
     */
    public function dateWise(Subject $subject, Request $request)
    {
        $teacher = auth()->user();
        
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $dateFilter = $request->get('date', now()->format('Y-m-d'));
        
        $attendances = Attendance::where('subject_id', $subject->id)
            ->where('attendance_date', $dateFilter)
            ->with('student')
            ->get()
            ->groupBy('status');

        $students = $subject->students()->orderBy('name')->get();
        
        // Get all attendances for this date
        $attendanceMap = Attendance::where('subject_id', $subject->id)
            ->where('attendance_date', $dateFilter)
            ->get()
            ->keyBy('student_id');

        return view('teacher.reports.date-wise', compact('subject', 'students', 'attendanceMap', 'dateFilter'));
    }

    /**
     * Display month-wise report.
     */
    public function monthWise(Subject $subject, Request $request)
    {
        $teacher = auth()->user();
        
        if (!$teacher->subjects->contains($subject)) {
            abort(403, 'You are not assigned to this subject.');
        }

        $monthFilter = $request->get('month', now()->format('Y-m'));
        $monthStart = date('Y-m-01', strtotime($monthFilter));
        $monthEnd = date('Y-m-t', strtotime($monthFilter));
        
        $students = $subject->students()->orderBy('name')->get();
        
        $studentsData = [];
        
        foreach ($students as $student) {
            $attendances = Attendance::where('subject_id', $subject->id)
                ->where('student_id', $student->id)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date')
                ->get();
            
            $stats = $this->calculateStudentStats($attendances);
            
            // Daily breakdown
            $dailyBreakdown = [];
            $startDate = new \DateTime($monthStart);
            $endDate = new \DateTime($monthEnd);
            
            while ($startDate <= $endDate) {
                $dateStr = $startDate->format('Y-m-d');
                $attendance = $attendances->where('attendance_date', $dateStr)->first();
                $dailyBreakdown[$dateStr] = $attendance ? $attendance->status : 'absent';
                $startDate->modify('+1 day');
            }
            
            $studentsData[] = [
                'student' => $student,
                'attendances' => $attendances,
                'stats' => $stats,
                'dailyBreakdown' => $dailyBreakdown,
            ];
        }

        return view('teacher.reports.month-wise', compact('subject', 'studentsData', 'monthFilter'));
    }

    /**
     * Calculate statistics for a student's attendances.
     */
    private function calculateStudentStats($attendances)
    {
        $total = $attendances->count();
        
        if ($total === 0) {
            return [
                'total' => 0,
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'present_percentage' => 0,
                'absent_percentage' => 0,
            ];
        }

        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();
        $excused = $attendances->where('status', 'excused')->count();
        
        // Present includes late and excused
        $effectivePresent = $present + $late + $excused;
        
        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'present_percentage' => round(($effectivePresent / $total) * 100, 2),
            'absent_percentage' => round(($absent / $total) * 100, 2),
        ];
    }
}

