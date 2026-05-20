<?php

namespace App\Domains\Tutor\Actions;

use App\Domains\Tutor\Ports\TutorRepositoryInterface;
use App\Domains\Tutor\Ports\TutorServicePort;
use App\Shared\Enums\TutorStatusEnum;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ApproveApplicationTutorAction
{
    public function __construct(
        protected TutorRepositoryInterface $repository,
        protected TutorServicePort $service
    ) {}

    public function execute(int $tutorId): void
    {
        $tutor = $this->repository->getTutorById($tutorId);

        if (!$tutor) {
            throw new ConflictHttpException('Tutor application not found.');
        } elseif ($tutor->status !== TutorStatusEnum::PENDING) {
            throw new ConflictHttpException('Tutor application is not in a pending state.');
        }

        $this->repository->update($tutorId, ['status' => TutorStatusEnum::VERIFIED]);

        $this->service->pushNotificationToUser($tutorId, [
            'title' => 'Application Approved',
            'body' => 'Congratulations! Your tutor application has been approved.',
            'data' => [
                'tutor_id' => $tutorId,
            ],
        ]);
    }
}