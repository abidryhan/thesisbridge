<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'tech_stack',
        'team_members',
        'term',
        'year',
        'course_name',
        'github_link',
        'demo_link',
        'screenshot_paths',
        'is_open_for_continuation',
        'continued_from_id',
    ];

    protected $casts = [
    'tech_stack' => 'array',
    'team_members' => 'array',
    'screenshot_paths' => 'array',
    'research_tags' => 'array',
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_project_student');
    }

    public function continuedFrom(): BelongsTo
    {
        return $this->belongsTo(CourseProject::class, 'continued_from_id');
    }

    public function continuations(): HasMany
    {
        return $this->hasMany(CourseProject::class, 'continued_from_id');
    }
}