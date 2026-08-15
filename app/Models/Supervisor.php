<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supervisor extends Model
{
    protected $fillable = [
        'user_id',
        'designation',
        'research_areas',
        'max_capacity',
    ];

    protected $casts = [
        'research_areas' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thesisGroups(): HasMany
    {
        return $this->hasMany(ThesisGroup::class);
    }

    public function currentLoad(): int
    {
        return $this->thesisGroups()->count();
    }

    public function compatibilityScoreWith(ThesisGroup $group): int
    {
        $proposal = $group->proposal;

        if (!$proposal || empty($proposal->research_tags) || empty($this->research_areas)) {
            return 0;
        }

        $supervisorTags = array_map('strtolower', $this->research_areas);
        $proposalTags = array_map('strtolower', $proposal->research_tags);

        $intersection = array_intersect($supervisorTags, $proposalTags);
        $union = array_unique(array_merge($supervisorTags, $proposalTags));

        if (empty($union)) {
            return 0;
        }

        return (int) round((count($intersection) / count($union)) * 100);
    }
}
