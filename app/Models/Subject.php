<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get the teachers assigned to this subject.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'teacher_id')
            ->withTimestamps();
    }

    /**
     * Get the students enrolled in this subject.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_id')
            ->withTimestamps();
    }

    /**
     * Get all attendances for this subject.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

