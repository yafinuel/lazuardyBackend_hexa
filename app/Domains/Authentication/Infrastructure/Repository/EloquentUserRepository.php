<?php

namespace App\Domains\Authentication\Infrastructure\Repository;

use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create user data
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    
    /**
     * Create user and student data.
     */
    public function createStudentData(array $userData, array $studentData): User
    {
        $user = User::create($userData);
        $studentData['user_id'] = $user->id;
        Student::create($studentData);
        return $user;
    }

    
    /**
     * Create user and tutor data.
     */
    public function createTutorData(array $userData, array $tutorData): User
    {
        $user = User::create($userData);
        $tutorData['user_id'] = $user->id;
        Tutor::create($tutorData);
        return $user;
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
