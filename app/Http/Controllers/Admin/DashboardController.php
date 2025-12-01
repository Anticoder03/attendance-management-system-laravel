<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware is now handled in the route definitions.
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'teachers' => User::where('role', 'teacher')->count(),
            'subjects' => Subject::count(),
            'assignments' => DB::table('teacher_subject')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

