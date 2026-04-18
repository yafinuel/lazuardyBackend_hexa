<?php

namespace App\Domains\Tutor\Infrastructure\Repository;

use App\Domains\Tutor\Entities\TutorEntity;
use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Models\Tutor;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EloquentTutorRepository implements TutorRepositoryInterface
{
    public function getTutorById(int $tutorId): TutorEntity
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
            price: $tutor->price,
            description: $tutor->description,
            bankCode: $tutor->bank_code,
            accountNumber: $tutor->account_number,
            learningMethod: $tutor->learning_method,
            status: $tutor->status?->displayName(),
            avgRate: $tutor->reviews_avg_rate ?? null,
        );
    }

    public function updateTutorBiodata(int $tutorId, array $data): void
    {
        $user = User::where('id', $tutorId)->firstOrFail();
        
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

            $user->tutor()->update([
                'education' => $data['education'] ?? $user->tutor->education,
                'description' => $data['description'] ?? $user->tutor->description,
                'bank_code' => $data['bankCode'] ?? $user->tutor->bank_code,
                'account_number' => $data['accountNumber'] ?? $user->tutor->account_number,
                'learning_method' => $data['learningMethod'] ?? $user->tutor->learning_method,
                'status' => $data['status'] ?? $user->tutor->status,
            ]);

        } catch (Exception $e) {

            Log::error("Message: " . $e->getMessage());
            throw new Exception('Failed to update student profile');
            
        }
    }

    public function getByCriteria(array $filters, int $paginate): LengthAwarePaginator
    {
        $query = Tutor::with(['user', 'subjects.class'])
            ->withAvg('reviews', 'rate');

        if(isset($filters['subject']) || isset($filters['class_name']) || isset($filters['level'])) {
            $query->whereHas('subjects', function($q) use ($filters) {

                if(isset($filters['subject'])) {
                    $q->where('name', $filters['subject']);
                }

                $q->whereHas('class', function($q2) use ($filters) {
                    if(isset($filters['class_name'])) {
                        $q2->where('name', $filters['class_name']);
                    }

                    if(isset($filters['level'])) {
                        $q2->where('level', $filters['level']);
                    }
                });
            });
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
                price: $tutor->price,
                description: $tutor->description,
                bankCode: $tutor->bank_code,
                accountNumber: $tutor->account_number,
                learningMethod: $tutor->learning_method,
                status: $tutor->status?->displayName(),
                avgRate: $tutor->reviews_avg_rate ?? null,
            );
        });
    }

    // public function createTutor(array $data)
    // {
    //     Tutor::create([
    //         'user_id' => $data['user_id'],
    //         'education' => $data['education'],
    //         'salary' => $data['salary'],
    //         'price' => $data['price'],
    //         'description' => $data['description'],
    //         'bank_code' => $data['bank_code'],
    //         'account_number' => $data['account_number'],
    //         'learning_method' => $data['learning_method'],
    //     ]);
    // }

    
}