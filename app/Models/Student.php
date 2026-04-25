<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = 
    [
        'user_id',
        'class_id',
        'session',
    ];

    protected $casts = [
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'student_id', 'user_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'student_id', 'user_id');
    }
    
    public function presences()
    {
        return $this->hasMany(Presence::class, 'student_id', 'user_id');
    }
}