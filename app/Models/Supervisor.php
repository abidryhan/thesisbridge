<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
        return $this->thesisGroups()
            ->get()
            ->reject(fn (ThesisGroup $group) => $group->isCompleted())
            ->count();
    }

    public function totalSupervised(): int
    {
        return $this->thesisGroups()->count();
    }

    public function averageCompletionTimeInDays(): ?float
    {
        $durations = $this->thesisGroups()
            ->get()
            ->map(fn (ThesisGroup $group) => $group->completionDurationInDays())
            ->filter();

        if ($durations->isEmpty()) {
            return null;
        }

        return round($durations->avg(), 1);
    }

    public function milestoneAdherenceRate(): ?float
    {
        $groupIds = $this->thesisGroups()->pluck('id');

        $resolvedMilestones = Milestone::whereIn('thesis_group_id', $groupIds)
            ->get()
            ->filter(fn (Milestone $milestone) => $milestone->completed_at !== null || $milestone->deadline->isPast());

        if ($resolvedMilestones->isEmpty()) {
            return null;
        }

        $onTime = $resolvedMilestones->filter(
            fn (Milestone $milestone) => $milestone->completed_at !== null && $milestone->completed_at->lte($milestone->deadline)
        )->count();

        return round(($onTime / $resolvedMilestones->count()) * 100, 1);
    }

    public function researchAreasCovered(): Collection
    {
        return $this->thesisGroups()
            ->get()
            ->map(fn (ThesisGroup $group) => $group->proposal?->research_tags ?? [])
            ->flatten()
            ->countBy()
            ->sortDesc();
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

    public function weeklyDigestData(): array
    {
        $today = \Illuminate\Support\Carbon::today();
        $sevenDaysOut = $today->copy()->addDays(7);

        $activeGroups = $this->thesisGroups()
            ->with('milestones')
            ->get()
            ->reject(fn (ThesisGroup $group) => $group->isCompleted());

        $upcomingMilestones = [];
        $overdueMilestones = [];
        $ghostGroups = [];

        foreach ($activeGroups as $group) {
            foreach ($group->milestones as $milestone) {
                if ($milestone->completed_at !== null) {
                    continue;
                }

                if ($milestone->deadline->lte($today)) {
                    $overdueMilestones[] = [
                        'group_name' => $group->group_name,
                        'milestone_title' => $milestone->title,
                        'deadline' => $milestone->deadline->toDateString(),
                    ];
                } elseif ($milestone->deadline->lte($sevenDaysOut)) {
                    $upcomingMilestones[] = [
                        'group_name' => $group->group_name,
                        'milestone_title' => $milestone->title,
                        'deadline' => $milestone->deadline->toDateString(),
                    ];
                }
            }

            if ($group->isGhost()) {
                $ghostGroups[] = [
                    'group_name' => $group->group_name,
                    'days_since_activity' => $group->daysSinceLastActivity(),
                ];
            }
        }

        return [
            'active_group_count' => $activeGroups->count(),
            'upcoming_milestones' => $upcomingMilestones,
            'overdue_milestones' => $overdueMilestones,
            'ghost_groups' => $ghostGroups,
            'is_all_clear' => empty($upcomingMilestones) && empty($overdueMilestones) && empty($ghostGroups),
        ];
    }

}
