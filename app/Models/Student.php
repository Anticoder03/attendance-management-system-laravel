<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'student_id',
        'email',
        'phone',
        'date_of_birth',
    ];

    /**
     * Get the subjects the student is enrolled in.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')
            ->withTimestamps();
    }

    /**
     * Get all attendances for this student.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

