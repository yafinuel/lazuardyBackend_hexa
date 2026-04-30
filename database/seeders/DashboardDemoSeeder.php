<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\User;
use App\Shared\Enums\RoleEnum;
use App\Shared\Enums\ScheduleStatusEnum;
use App\Shared\Enums\TutorStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DashboardDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentUser = User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'demo.student@example.com',
            'role' => RoleEnum::STUDENT,
        ]);

        $tutorUser = User::factory()->create([
            'name' => 'Demo Tutor',
            'email' => 'demo.tutor@example.com',
            'role' => RoleEnum::TUTOR,
        ]);

        $studentClassId = ClassModel::where('level', 'sd')->orderBy('id')->value('id');
        $subject = Subject::where('class_id', $studentClassId)->orderBy('id')->first();

        if (! $studentClassId || ! $subject) {
            return;
        }

        Student::create([
            'user_id' => $studentUser->id,
            'class_id' => $studentClassId,
            'session' => 0,
        ]);

        Tutor::create([
            'user_id' => $tutorUser->id,
            'education' => [['level' => 'S1', 'major' => 'Pendidikan Matematika']],
            'salary' => 0,
            'description' => 'Tutor demo untuk pengujian dashboard schedule.',
            'learning_method' => ['online', 'offline'],
            'status' => TutorStatusEnum::VERIFIED,
        ]);

        Schedule::create([
            'tutor_id' => $tutorUser->id,
            'student_id' => $studentUser->id,
            'subject_id' => $subject->id,
            'date' => Carbon::now()->toDateString(),
            'time' => '21:00:00',
            'reason' => 'Latihan soal dan review materi',
            'learning_method' => 'online',
            'address' => 'Google Meet',
            'status' => ScheduleStatusEnum::PENDING,
        ]);

        Schedule::create([
            'tutor_id' => $tutorUser->id,
            'student_id' => $studentUser->id,
            'subject_id' => $subject->id,
            'date' => Carbon::now()->addDay()->toDateString(),
            'time' => '22:00:00',
            'reason' => 'Pembahasan PR',
            'learning_method' => 'offline',
            'address' => 'Ruang belajar demo',
            'status' => ScheduleStatusEnum::ACTIVE,
        ]);
    }
}