<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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

    protected $casts = [
        'research_tags' => 'array',
    ];

    protected const TRANSITIONS = [
        'submitted' => ['under_review'],
        'under_review' => ['approved', 'revision_required', 'rejected'],
        'revision_required' => ['submitted'],
        'rejected' => ['submitted'],
        'approved' => [],
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
}