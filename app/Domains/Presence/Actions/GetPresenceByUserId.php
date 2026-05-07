<?php

namespace App\Domains\Presence\Actions;

use App\Domains\Presence\Entities\PresenceEntity;
use App\Domains\Presence\Ports\PresenceRepositoryInterface;
use App\Domains\Presence\Ports\PresenceServicePort;
use App\Shared\Enums\RoleEnum;

class GetPresenceByUserId
{
    public function __construct(protected PresenceRepositoryInterface $repository, protected PresenceServicePort $service) {}

    public function execute(int $userId, array $data)
    {
        $user = $this->service->userBiodata($userId);
        
        if ($user->role === RoleEnum::PARENT) {
            $userId = $this->service->parentBiodata($userId)->studentId;
        }

        $result = $this->repository->getPresencesByUserId($userId, null, $data['paginate'] ?? 10);

        return $result->through(function ($report) {
            return new PresenceEntity(
                id: $report->id,
                scheduleId: $report->schedule_id,
                tutorId: $report->tutor_id,
                tutorName: $report->tutor->user->name,
                studentId: $report->student_id,
                studentName: $report->student->user->name,
                topic: $report->topic,
                notes: $report->notes,
                createdAt: $report->created_at,
                updatedAt: $report->updated_at,
            );
        });
    }
}