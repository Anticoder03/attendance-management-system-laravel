<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct()
    {
        // Middleware is now handled in the route definitions.
    }

    /**
     * Display a listing of teacher-subject assignments.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')->with('subjects')->get();
        $subjects = Subject::all();
        
        return view('admin.assignments.index', compact('teachers', 'subjects'));
    }

    /**
     * Store a new teacher-subject assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacher = User::findOrFail($validated['teacher_id']);
        
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'Selected user is not a teacher.');
        }

        // Check if assignment already exists
        if ($teacher->subjects()->where('subject_id', $validated['subject_id'])->exists()) {
            return back()->with('error', 'This assignment already exists.');
        }

        $teacher->subjects()->attach($validated['subject_id']);

        return back()->with('success', 'Teacher assigned to subject successfully.');
    }

    /**
     * Remove a teacher-subject assignment.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacher = User::findOrFail($validated['teacher_id']);
        $teacher->subjects()->detach($validated['subject_id']);

        return back()->with('success', 'Assignment removed successfully.');
    }
}

