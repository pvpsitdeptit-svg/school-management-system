<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Exam;
use App\Models\Mark;

class Student extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'admission_no',
        'class_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function parentStudents(): HasMany
    {
        return $this->hasMany(ParentStudent::class);
    }

    public function parents()
    {
        return $this->hasManyThrough(ParentModel::class, ParentStudent::class, 'student_id', 'id', 'id', 'parent_id');
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany(StudentClassHistory::class);
    }

    public function currentClassHistory()
    {
        return $this->hasOne(StudentClassHistory::class)->where('status', 'active');
    }
}
