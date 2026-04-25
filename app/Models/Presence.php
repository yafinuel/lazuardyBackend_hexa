<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'schedule_id',
        'tutor_id',
        'student_id',
        'topic',
        'notes',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }
}
