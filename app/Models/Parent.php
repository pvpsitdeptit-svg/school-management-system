<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentModel extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'name',
        'phone',
        'occupation',
        'address',
        'relationship',
    ];

    protected $casts = [
        'relationship' => 'string',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(ParentStudent::class);
    }

    public function studentDetails(): HasMany
    {
        return $this->hasManyThrough(Student::class, ParentStudent::class, 'parent_id', 'id', 'id', 'student_id');
    }
}
