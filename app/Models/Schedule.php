<?php

namespace App\Models;

use App\Shared\Enums\ScheduleStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'tutor_id',
        'student_id',
        'subject_id',
        'date',
        'time',
        'reason',
        'learning_method',
        'address',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => ScheduleStatusEnum::class,
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
