<?php

namespace App\Domains\Tutor\Infrastructure\Repository;

use App\Domains\Tutor\Entities\TutorEntity;
use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Models\Tutor;

class EloquentTutorRepository implements TutorRepositoryInterface
{
    public function getByCriteria(array $filters)
    {
        $query = Tutor::with(['user', 'subjects.class']);

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

        $paginator = $query->paginate(10);

        $tutors = collect($paginator->items())->map(function (Tutor $tutor) {
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
        })->toArray();
        
        return [
            'tutors' => $tutors,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
}