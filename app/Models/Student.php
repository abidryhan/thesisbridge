<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
