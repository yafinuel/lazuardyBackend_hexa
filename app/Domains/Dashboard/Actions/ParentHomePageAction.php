<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;

class ParentHomePageAction
{
    public function __construct(protected DashboardServicePort $service) {}

    public function execute(int $parentId, int $notifPaginate = 2): array
    {
        $parent = $this->service->parentBiodata($parentId);

        $warning = $this->service->getUserWarning($parent->studentId);
        $studentBiodata = $this->service->studentBiodata($parent->studentId);
        $notificationData = $this->service->getNotification($parent->studentId, $notifPaginate);
        $schedulesHistory = $this->service->getSchedulesByUserId(
            $parent->studentId, 
            ['status' => ['completed', 'cancelled','expired', 'rejected']],
            2
        );

        $notifications = $notificationData->through(function ($notifEntity) {
            return [
                'id' => $notifEntity->getId(),
                'title' => $notifEntity->getTitle(),
                'body' => $notifEntity->getBody(),
                'data' => $notifEntity->getData(),
            ];
        });

        return [
            'me' => [
                'id' => $parent->id,
                'email' => $parent->email,
            ],
            'warning' => $warning,
            'sanction' => $studentBiodata->sanction,
            'session' => $studentBiodata->session,
            'notification' => $notifications,
            'schedules_history' => $schedulesHistory,
        ];
    }
}