<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supervisor extends Model
{
    protected $fillable = [
        'user_id',
        'designation',
        'research_areas',
        'max_capacity',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
