<?php

namespace App\Domains\UserProfile\Student\Infrastructure\Repository;

use App\Domains\UserProfile\Student\Entities\StudentEntity;
use App\Domains\UserProfile\Student\Ports\StudentRepositoryInterface;
use App\Models\User;

class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function getStudentProfile(int $studentId): ?StudentEntity
    {
        $user = User::where('id', $studentId)->firstOrFail();
        $student = $user->student;

        return new StudentEntity(
            id: $student->user_id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            googleId: $user->google_id,
            facebookId: $user->facebook_id,
            role: $user->role->displayName(),
            profilePhotoPath: $user->profile_photo_url,
            gender: $user->gender?->displayName(),
            dateOfBirth: $user->date_of_birth,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            religion: $user->religion?->displayName(),
            homeAddress: $user->home_address,
            latitude: $user->latitude,
            longitude: $user->longitude,
            session: $student->session,
            classId: $student->class_id,
        );
    }

    public function updateStudentProfile(int $studentId, array $data): void
    {
        throw new \Exception('Not implemented');
    }
}
