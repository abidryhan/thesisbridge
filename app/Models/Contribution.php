<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    protected $fillable = [
        'course_project_id',
        'student_id',
        'description',
        'percentage',
    ];

    public function courseProject(): BelongsTo
    {
        return $this->belongsTo(CourseProject::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
