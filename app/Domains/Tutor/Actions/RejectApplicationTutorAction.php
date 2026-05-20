<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Domains\Tutor\Ports\TutorServicePort;
use App\Shared\Enums\TutorStatusEnum;

class RejectApplicationTutorAction
{
    public function __construct(
        protected TutorRepositoryInterface $repository,
        protected TutorServicePort $service
    ) {}

    public function execute(int $tutorId)
    {
        $tutor = $this->repository->getTutorById($tutorId);

        if (!$tutor) {
            return;
        } elseif ($tutor->status !== TutorStatusEnum::PENDING) {
            return;
        }
        
        $this->repository->update($tutorId, ['status' => TutorStatusEnum::REJECTED]);
        
        $this->service->pushNotificationToUser($tutorId, [
            'title' => 'Lamaran Ditolak',
            'body' => 'Maaf, lamaran tutor Anda tidak memenuhi kriteria kami. Silakan coba lagi di lain waktu.',
            'data' => [
                'tutor_id' => $tutorId,
            ],
        ]);
    }
}