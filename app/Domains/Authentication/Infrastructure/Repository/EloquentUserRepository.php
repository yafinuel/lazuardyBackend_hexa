<?php

namespace App\Domains\Authentication\Infrastructure\Repository;

use App\Domains\Authentication\Entities\UserAuthEntity;
use App\Domains\Authentication\Ports\UserRepositoryInterface;
use App\Models\Student;
use App\Models\User;
use App\Shared\Enums\RoleEnum;
use App\Shared\Ports\TaskQueueInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(protected TaskQueueInterface $queue) {}

    public function getToken(int $id): string
    {
        $user = User::findOrFail($id);
        $token = $user->createToken('manual_auth_token')->plainTextToken;

        return $token;
    }
    
    /**
     * Create user and student data.
     */
    public function createStudentData(array $data): UserAuthEntity
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => $data['email_verified_at'],
                'role' => RoleEnum::STUDENT,
                'password' => Hash::make($data['password']),
                'google_id' => $data['google_id'],
                'facebook_id' => $data['facebook_id'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'telephone_number' => $data['telephone_number'],
                'home_address' => $data['home_address'],
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $data['class_id'],
            ]);

            DB::commit();
            return new UserAuthEntity(
                id: $user->id,
                email: $user->email,
                emailVerifiedAt: $user->email_verified_at,
                password: $user->password,
                google_id: $user->google_id,
                facebook_id: $user->facebook_id,
            );
        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Message: " . $e->getMessage());
            throw new Exception("Gagal melakukan registrasi, silakan cek kembali data Anda.");
        }
    }
    
    public function resetPassword(string $email, string $password): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new Exception("User dengan email tersebut tidak ditemukan.", 404);
        }

        $user->update([
            'password' => Hash::make($password)
        ]);
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
