<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Auth & role middleware now handled in routes/web.php
    }

    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $teacher = auth()->user();
        $subjects = $teacher->subjects;
        
        $stats = [
            'subjects' => $subjects->count(),
            'total_attendances' => $teacher->attendances()->count(),
        ];

        return view('teacher.dashboard', compact('subjects', 'stats'));
    }
}

