<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function __construct()
    {
        // Middleware is now handled in the route definitions.
    }

    /**
     * Display a listing of students.
     */
    public function index()
    {
        $students = Student::latest()->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('admin.students.create', compact('subjects'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:255|unique:students,student_id',
            'email' => 'nullable|email|max:255|unique:students,email',
            'phone' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $student = Student::create($validated);

        if ($request->has('subjects')) {
            $student->subjects()->attach($request->subjects);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Show the form for editing a student.
     */
    public function edit(Student $student)
    {
        $subjects = Subject::all();
        $student->load('subjects');
        return view('admin.students.edit', compact('student', 'subjects'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
            'phone' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $student->update($validated);

        if ($request->has('subjects')) {
            $student->subjects()->sync($request->subjects);
        } else {
            $student->subjects()->detach();
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}

