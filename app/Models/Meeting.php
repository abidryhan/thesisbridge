<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    protected $fillable = [
        'thesis_group_id',
        'milestone_id',
        'logged_by_user_id',
        'meeting_date',
        'agenda',
        'outcomes',
        'next_action_items',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function thesisGroup(): BelongsTo
    {
        return $this->belongsTo(ThesisGroup::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by_user_id');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}