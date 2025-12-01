<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ReportController;
use Illuminate\Support\Facades\Route;

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.store');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Teachers Management
    Route::resource('teachers', TeacherController::class);
    
    // Subjects Management
    Route::resource('subjects', SubjectController::class);
    
    // Students Management
    Route::resource('students', StudentController::class);
    
    // Assignments (Teacher-Subject)
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::delete('/assignments', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
});

// Teacher Routes
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Attendance
    Route::get('/attendance/{subject}/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/{subject}', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{subject}/history', [AttendanceController::class, 'history'])->name('attendance.history');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{subject}/individual', [ReportController::class, 'individual'])->name('reports.individual');
    Route::get('/reports/{subject}/grouped', [ReportController::class, 'grouped'])->name('reports.grouped');
    Route::get('/reports/{subject}/date-wise', [ReportController::class, 'dateWise'])->name('reports.date-wise');
    Route::get('/reports/{subject}/month-wise', [ReportController::class, 'monthWise'])->name('reports.month-wise');
});
