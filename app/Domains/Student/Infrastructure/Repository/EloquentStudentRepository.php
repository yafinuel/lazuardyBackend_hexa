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
        $user = User::where('id', $studentId)->firstOrFail();
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
        );
    }

    public function updateStudentProfile(int $studentId, array $data): void
    {
        $user = User::where('id', $studentId)->firstOrFail();

        try {
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'date_of_birth' => $data['date_of_birth'] ?? $user->date_of_birth,
                'gender' => $data['gender'] ?? $user->gender,
                'religion' => $data['religion'] ?? $user->religion,
                'home_address' => $data['home_address'] ?? $user->home_address,
                'telephone_number' => $data['telephone_number'] ?? $user->telephone_number,
                'latitude' => $data['latitude'] ?? $user->latitude,
                'longitude' => $data['longitude'] ?? $user->longitude,
            ]);
            
            $user->student()->update([
                'class_id' => $data['class_id'] ?? $user->student->class_id,
            ]);

        } catch (Exception $e) {
            Log::error("Message: " . $e->getMessage());
            throw new Exception('Failed to update student profile');
        }
    }
}