<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    protected $fillable = [
        'thesis_group_id',
        'title',
        'description',
        'deadline',
        'deliverable_type',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function thesisGroup(): BelongsTo
    {
        return $this->belongsTo(ThesisGroup::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }
}