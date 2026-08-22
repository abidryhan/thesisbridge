<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'department',
        'batch',
        'academic_year',
        'research_interests',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thesisGroups(): BelongsToMany
    {
        return $this->belongsToMany(ThesisGroup::class);
    }

    public function scopeAvailableForGroup(Builder $query): void
    {
        $query->whereDoesntHave('thesisGroups');
    }

    public function courseProjects(): BelongsToMany
    {
        return $this->belongsToMany(CourseProject::class, 'course_project_student');
    }

    public function skillFingerprint(): Collection
    {
        return $this->courseProjects()
            ->get(['tech_stack'])
            ->flatMap(fn ($project) => $project->tech_stack)
            ->countBy()
            ->sortDesc();
    }
}
