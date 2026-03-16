<?php

namespace App\Domains\UserProfile\User\Infrastructure\Repository;

use App\Domains\UserProfile\User\Infrastructure\Job\ProcessPhotoProfileJob;
use App\Domains\UserProfile\User\Ports\UserRepositoryInterface;
use App\Models\User;
use App\Shared\Ports\TaskQueueInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(protected TaskQueueInterface $queue) {}

    public function updateRawProfilePhoto(int $userId, string $url)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'profile_photo_url' => $url,
        ]);
    }

    public function updateProfilePhoto(int $userId, array $data)
    {
        $user = User::findOrFail($userId);

        DB::beginTransaction();
        try {
            $this->updateRawProfilePhoto($userId, $data['profile_picture_url']);
            DB::commit();

            DB::afterCommit(function() use ($data, $user) {
                $this->queue->dispatch(new ProcessPhotoProfileJob($user->id, $data['profile_picture_url'], 'profile_pictures/' . $user->id));
            });

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Message: " . $e->getMessage());
            throw new Exception("Failed to update profile picture");
        }
    }
}
