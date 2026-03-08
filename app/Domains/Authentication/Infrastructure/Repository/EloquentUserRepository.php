<?php

namespace App\Domains\Authentication\Infrastructure\Repository;

use App\Domains\Authentication\Entities\UserAuthEntity;
use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserAuthEntity
    {
        $user = User::where('email', $email)->first();
        
        if(!$user) return null;
        
        return new UserAuthEntity(
            id: $user->id,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            password: $user->password,
            google_id: $user->google_id,
            facebook_id: $user->facebook_id,
        );
    }

    public function getToken(int $id): string
    {
        $user = User::findOrFail($id);
        $token = $user->createToken('manual_auth_token')->plainTextToken;

        return $token;
    }

    /**
     * Create user data
     */
    public function create(array $data): void
    {
        $user = User::create($data);
    }

    
    /**
     * Create user and student data.
     */
    public function createStudentData(array $userData, array $studentData): void
    {
        $user = User::create($userData);
        $studentData['user_id'] = $user->id;
        Student::create($studentData);
    }

    
    /**
     * Create user and tutor data.
     */
    public function createTutorData(array $userData, array $tutorData): void
    {
        $user = User::create($userData);
        $tutorData['user_id'] = $user->id;
        Tutor::create($tutorData);
    }

    
    /**
     * update social_id if email exists.
     */
    public function updateSocialId(int $userId, string $provider, string $providerId): void
    {
        $provider = $provider . "_id";
        User::where('id', $userId)->update([
            $provider . "_id" => $providerId
        ]);
    }
}
