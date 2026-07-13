<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}