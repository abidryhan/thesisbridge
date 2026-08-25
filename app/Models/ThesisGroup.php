<?php

namespace App\Models;
use App\Models\Document;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ThesisGroup extends Model
{
    protected $fillable = [
        'group_name',
        'supervisor_id',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function proposal(): HasOne
    {
        return $this->hasOne(Proposal::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function isSupervisedBy(Supervisor $supervisor): bool
    {
        return $this->supervisor_id === $supervisor->id;
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function isAccessibleBy(User $user): bool
    {
        if ($user->supervisor && $this->isSupervisedBy($user->supervisor)) {
            return true;
        }

        if ($user->student && $this->students->contains('id', $user->student->id)) {
            return true;
        }

        return false;
    }

    public function lastStudentActivityAt(): ?Carbon
    {
        $lastDocument = Document::whereHas('milestone', fn ($query) => $query->where('thesis_group_id', $this->id))
            ->whereHas('user.student')
            ->latest('created_at')
            ->first();

        $lastMeeting = $this->meetings()
            ->whereHas('loggedBy.student')
            ->latest('created_at')
            ->first();

        return collect([$lastDocument?->created_at, $lastMeeting?->created_at])
            ->filter()
            ->max();
    }

    public function daysSinceLastActivity(): int
    {
        $referenceDate = $this->lastStudentActivityAt() ?? $this->created_at;

        return (int) $referenceDate->diffInDays(now());
    }

    public function isGhost(): bool
    {
        return $this->daysSinceLastActivity() > config('thesisbridge.ghost_threshold_days');
    }

    public function completedAt(): ?Carbon
    {
        $milestones = $this->milestones;

        if ($milestones->isEmpty() || $milestones->contains(fn ($milestone) => is_null($milestone->completed_at))) {
            return null;
        }

        return $milestones->max('completed_at');
    }

    public function isCompleted(): bool
    {
        return $this->completedAt() !== null;
    }

    public function completionDurationInDays(): ?int
    {
        $completedAt = $this->completedAt();
        $approvedAt = $this->proposal?->approvedAt();

        if (!$completedAt || !$approvedAt) {
            return null;
        }

        return (int) $approvedAt->diffInDays($completedAt);
    }

}