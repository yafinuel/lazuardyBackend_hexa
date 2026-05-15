<?php

namespace App\Domains\Review\Actions;

use App\Domains\Review\Ports\ReviewRepositoryInterface;
use App\Domains\Review\Ports\ReviewServicePort;

class CreateReviewAction
{
    public function __construct(
        protected ReviewRepositoryInterface $repository,
        protected ReviewServicePort $service
    ) {}

    public function execute(int $studentId ,array $data)
    {
        $student = $this->service->getUserById($studentId);

        $toDb = [
            'tutor_id' => $data['tutor_id'],
            'rate' => $data['rate'],
            'comment' => $data['comment'] ?? null,
        ];
        $this->repository->create($studentId, $toDb);

        $notificationData = [
            'title' => 'Menerima Review Baru',
            'body' => "Anda menerima review baru dengan rating {$data['rate']} dari seorang siswa bernama {$student->name}",
            'data' => [
                'tutor_id' => $data['tutor_id'],
                'student_id' => $studentId,
                'rate' => $data['rate'],
                'comment' => $data['comment'] ?? '',
            ],
        ];
        
        $this->service->pushNotificationToUser($data['tutor_id'], $notificationData);
    }
}