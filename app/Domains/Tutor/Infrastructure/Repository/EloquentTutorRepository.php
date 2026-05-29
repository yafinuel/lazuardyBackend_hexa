<?php

namespace App\Domains\Tutor\Infrastructure\Repository;

use App\Domains\Tutor\Entities\TutorEntity;
use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Models\Tutor;
use App\Models\User;
use App\Shared\Enums\RoleEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EloquentTutorRepository implements TutorRepositoryInterface
{
    public function getTutorById(int $tutorId): TutorEntity
    {
        $user = User::with('tutor.subjects')->where('id', $tutorId)->firstOrFail();
        $tutor = $user->tutor;
        
        return new TutorEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            telephoneNumber: $user->telephone_number,
            telephoneVerifiedAt: $user->telephone_verified_at,
            profilePhotoUrl: $user->profile_photo_path,
            dateOfBirth: $user->date_of_birth,
            gender: $user->gender?->displayName(),
            religion: $user->religion?->displayName(),
            homeAddress: $user->home_address,
            warning: $user->warning,
            sanction: $user->sanction,
            latitude: $user->latitude,
            longitude: $user->longitude,
            education: $tutor->education,
            salary: $tutor->salary,
            role: $user->role?->displayName(),
            description: $tutor->description,
            accountHolderName: $tutor->account_holder_name,
            bankCode: $tutor->bank_code,
            accountNumber: $tutor->account_number,
            learningMethod: $tutor->learning_method,
            status: $tutor->status,
            avgRate: $tutor->reviews_avg_rate ?? null,
            createdAt: $tutor->created_at,
            subjects: $tutor->subjects->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'class' => [
                        'id' => $subject->class->id,
                        'name' => $subject->class->name,
                        'level' => $subject->class->level,
                    ],
                ];
            })->toArray(),
        );
    }

    public function update(int $tutorId, array $data): void
    {
        $tutor = Tutor::where('user_id', $tutorId)->firstOrFail();

        $tutor->update($data);
    }

    public function getByCriteria(array $filters, int $paginate): LengthAwarePaginator
    {
        $query = Tutor::with(['user', 'subjects.class'])
            ->withAvg('reviews', 'rate');

        if(isset($filters['subject']) || isset($filters['class_name']) || isset($filters['level'])) {
            $query->whereHas('subjects', function($q) use ($filters) {

                if(isset($filters['subject_id'])) {
                    $q->where('id', $filters['subject_id']);
                }

                if(isset($filters['subject_name'])) {
                    $q->where('name', $filters['subject_name']);
                }

                $q->whereHas('class', function($q2) use ($filters) {
                    if(isset($filters['class_id'])) {
                        $q2->where('id', $filters['class_id']);
                    }

                    if(isset($filters['class_name'])) {
                        $q2->where('name', $filters['class_name']);
                    }

                    if(isset($filters['level'])) {
                        $q2->where('level', $filters['level']);
                    }
                });
            });
        }

        // Apply rating filters if provided (min_rate, max_rate refer to average review rate)
        if (isset($filters['min_rate'])) {
            $query->havingRaw('COALESCE(reviews_avg_rate, 0) >= ?', [$filters['min_rate']]);
        }

        if (isset($filters['max_rate'])) {
            $query->havingRaw('COALESCE(reviews_avg_rate, 0) <= ?', [$filters['max_rate']]);
        }

        $query->orderByDesc('reviews_avg_rate');

        $paginator = $query->paginate($paginate);

        return $paginator->through(function (Tutor $tutor) {
            $user = $tutor->user;
            return new TutorEntity(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                emailVerifiedAt: $user->email_verified_at,
                telephoneNumber: $user->telephone_number,
                telephoneVerifiedAt: $user->telephone_verified_at,
                profilePhotoUrl: $user->profile_photo_path,
                dateOfBirth: $user->date_of_birth,
                gender: $user->gender?->displayName(),
                religion: $user->religion?->displayName(),
                homeAddress: $user->home_address,
                warning: $user->warning,
                sanction: $user->sanction,
                latitude: $user->latitude,
                longitude: $user->longitude,
                education: $tutor->education,
                salary: $tutor->salary,
                role: $user->role?->displayName(),

                description: $tutor->description,
                bankCode: $tutor->bank_code,
                accountHolderName: $tutor->account_holder_name,
                accountNumber: $tutor->account_number,
                learningMethod: $tutor->learning_method,
                status: $tutor->status,
                avgRate: $tutor->reviews_avg_rate ?? null,
                createdAt: $tutor->created_at,
            );
        });
    }

    public function createTutor(int $userId,array $data): int
    {
        $user = User::where('id', $userId)->firstOrFail();

        $tutor = $user->tutor()->create($data);

        $user->update([
            'role' => RoleEnum::TUTOR,
        ]);
        Log::info($data);
        return $tutor->user_id;
    }

}