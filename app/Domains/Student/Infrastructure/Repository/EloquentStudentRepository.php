<?php

namespace App\Domains\Student\Infrastructure\Repository;

use App\Domains\Student\Entities\StudentEntity;
use App\Domains\Student\Ports\StudentRepositoryInterface;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function getStudentById(int $studentId): ?StudentEntity
    {
        $user = User::with('student.class')->where('id', $studentId)->firstOrFail();
        $student = $user->student;

        return new StudentEntity(
            id: $student->user_id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            role: $user->role->displayName(),
            profilePhotoUrl: $user->profile_photo_path,
            gender: $user->gender?->displayName(),
            dateOfBirth: $user->date_of_birth,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            religion: $user->religion?->displayName(),
            homeAddress: $user->home_address,
            warning: $user->warning,
            sanction:$user->sanction,
            latitude: $user->latitude,
            longitude: $user->longitude,
            session: $student->session,
            classId: $student->class_id,
            className: $student->class?->name,
        );
    }

    public function createStudent(int $userId, array $studentData): int
    {
        $student = Student::create([
            'user_id' => $userId,
            'session' => $studentData['session'],
            'class_id' => $studentData['class_id'],
        ]);

        return $student->user_id;
    }

    public function update(int $userId, array $data): void
    {
        Student::where('user_id', $userId)->update($data);
    }

}