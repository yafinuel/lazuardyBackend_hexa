<?php

namespace App\Domains\User\Infrastructure\Repository;

use App\Domains\User\Entities\UserEntity;
use App\Domains\User\Ports\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function createUser(array $data): UserEntity
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => $data['email_verified_at'],
            'password' => Hash::make($data['password']),
            'google_id' => $data['google_id'],
            'facebook_id' => $data['facebook_id'],
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'telephone_number' => $data['telephone_number'],
            'home_address' => $data['home_address'],
            'profile_photo_path' => $data['profile_photo_temp_path'] ?? 'profile_photo/default/default.jpg',
            'role' => $data['role'],
        ]);

        return new UserEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            password: null,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            profilePhotoUrl: $user->profile_photo_path,
            dateOfBirth: $user->date_of_birth, 
            gender: $user->gender?->displayName(),
            religion: $user->religion,
            homeAddress: $user->home_address,
            role: $user->role,
            warning: $user->warning,
            sanction: $user->sanction,
            latitude: $user->latitude,
            longitude: $user->longitude,
        );
    }

    public function getUserById(int $userId): UserEntity
    {
        $user = User::findOrFail($userId);
        
        return new UserEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            password: null,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            profilePhotoUrl: $user->profile_photo_path,
            dateOfBirth: $user->date_of_birth, 
            gender: $user->gender?->displayName(),
            religion: $user->religion,
            homeAddress: $user->home_address,
            role: $user->role,
            warning: $user->warning,
            sanction: $user->sanction,
            latitude: $user->latitude,
            longitude: $user->longitude,
        );
    }

    public function getUserByEmail(string $email): UserEntity
    {
        $user = User::where('email', $email)->firstOrFail();
        
        return new UserEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            password: $user->password,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            profilePhotoUrl: $user->profile_photo_path,
            dateOfBirth: $user->date_of_birth, 
            gender: $user->gender?->displayName(),
            religion: $user->religion,
            homeAddress: $user->home_address,
            role: $user->role,
            warning: $user->warning,
            sanction: $user->sanction,
            latitude: $user->latitude,
            longitude: $user->longitude,
        );
    }

    public function resetPassword(string $email, string $newPassword): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception("User dengan email tersebut tidak ditemukan.", 404);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function updateSocialId(int $userId, string $provider, string $providerId): void
    {
        $providerColumn = $provider . "_id";
        User::where('id', $userId)->update([
            $providerColumn => $providerId
        ]);
    }

    public function update(int $userId, array $data): void
    {
        User::where('id', $userId)->update($data);
    }
}