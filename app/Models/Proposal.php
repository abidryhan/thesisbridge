<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function thesisGroup(): BelongsTo
    {
        return $this->belongsTo(ThesisGroup::class);
    }
}
