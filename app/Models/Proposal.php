<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use App\Models\CourseProject;
use Illuminate\Support\Collection;

class Proposal extends Model
{
    protected $fillable = [
        'thesis_group_id',
        'title',
        'abstract',
        'objectives',
        'methodology',
        'status',
        'research_tags',
    ];

    protected const TRANSITIONS = [
        'submitted' => ['under_review'],
        'under_review' => ['approved', 'revision_required', 'rejected'],
        'revision_required' => ['submitted'],
        'rejected' => ['submitted'],
        'approved' => [],
    ];

    protected $casts = [
        'research_tags' => 'array',
    ];

    public function thesisGroup(): BelongsTo
    {
        return $this->belongsTo(ThesisGroup::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(ProposalStatusHistory::class)->latest();
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::TRANSITIONS[$this->status] ?? [], true);
    }

    public function transitionTo(string $newStatus, ?string $reason, User $actor): void
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Cannot transition proposal from \"{$this->status}\" to \"{$newStatus}\"."
            );
        }

        DB::transaction(function () use ($newStatus, $reason, $actor) {
            $this->update(['status' => $newStatus]);

            $this->statusHistory()->create([
                'status' => $newStatus,
                'reason' => $reason,
                'changed_by_user_id' => $actor->id,
            ]);
        });
    }

    public static function researchThreadMap(): Collection
    {
        $proposals = self::where('status', 'approved')
            ->get()
            ->filter(fn ($proposal) => !empty($proposal->research_tags));

        $courseProjects = CourseProject::all()
            ->filter(fn ($project) => !empty($project->research_tags));

        $entries = collect();

        foreach ($proposals as $proposal) {
            foreach ($proposal->research_tags as $tag) {
                $entries->push([
                    'tag' => $tag,
                    'type' => 'thesis',
                    'title' => $proposal->title,
                    'created_at' => $proposal->created_at,
                    'model' => $proposal,
                ]);
            }
        }

        foreach ($courseProjects as $project) {
            foreach ($project->research_tags as $tag) {
                $entries->push([
                    'tag' => $tag,
                    'type' => 'course_project',
                    'title' => $project->title,
                    'created_at' => $project->created_at,
                    'model' => $project,
                ]);
            }
        }

        return $entries
            ->groupBy('tag')
            ->map(fn ($group) => $group->sortByDesc('created_at')->values())
            ->sortKeys();
    }
}