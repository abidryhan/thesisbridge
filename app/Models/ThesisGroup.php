<?php

namespace App\Models;

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
}