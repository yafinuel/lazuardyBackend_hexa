<?php

namespace App\Domains\UserProfile\Tutor\Infrastructure\Repository;

use App\Domains\UserProfile\Tutor\Entities\TutorEntity;
use App\Domains\UserProfile\Tutor\Ports\TutorRepositoryInterface;
use App\Models\User;

class EloquentTutorRepository implements TutorRepositoryInterface
{
    public function getTutorBiodata(int $tutorId): TutorEntity
    {
        $user = User::where('id', $tutorId)->firstOrFail();
        $tutor = $user->tutor;
        
        return new TutorEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            profilePhotoUrl: $user->profile_photo_url,
            dateOfBirth: $user->date_ofbirth,
            gender: $user->gender?->displayName(),
            religion: $user->religion?->displayName(),
            homeAddress: $user->home_address,
            latitude: $user->latitude,
            longitude: $user->longitude,
            education: $tutor->education,
            salary: $tutor->salary,
            price: $tutor->price,
            description: $tutor->description,
            bankCode: $tutor->bank_code,
            accountNumber: $tutor->account_number,
            learningMethod: $tutor->learning_method,
            sanction: $tutor->sanction,
            status: $tutor->status?->displayName(),
        );
    }

    public function getTutorFile(int $tutorId): array
    {
        throw new \Exception('Not implemented');
    }

    public function updateTutorBiodata(int $tutorId, array $data): void
    {
        throw new \Exception('Not implemented');
    }
}
