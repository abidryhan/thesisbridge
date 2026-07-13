<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseProject extends Model
{
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
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'team_members' => 'array',
        'screenshot_paths' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}