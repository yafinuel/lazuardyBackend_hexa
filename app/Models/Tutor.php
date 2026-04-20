<?php

namespace App\Models;

use App\Shared\Enums\TutorStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tutor extends Model
{
    /** @use HasFactory<\Database\Factories\TutorFactory> */
    use HasFactory;

    protected $table = 'tutors';
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = 
    [
        'user_id',
        'education',
        'salary',
        'price',
        'description',
        'learning_method',
        'status',
        'warning',
        'sanction',
        'bank_code',
        'account_number',
    ];
    
    protected $casts = [
        'qualification' => 'array',
        'organization' => 'array',
        'education' => 'array',
        'learning_method' => 'array',
        'status' => TutorStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'tutor_subjects', 'tutor_id', 'subject_id');
    }

    public function tutorSchedules()
    {
        return $this->hasMany(ScheduleTutor::class, 'tutor_id', 'user_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'tutor_id', 'user_id');
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'tutor_id', 'user_id');
    }
}
