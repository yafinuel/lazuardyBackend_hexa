<?php

namespace App\Domains\Dashboard\Actions;

use App\Domains\Dashboard\Ports\DashboardServicePort;
use App\Domains\FileManager\Ports\FileStorageInterface;

class StudentHomePageAction
{
    public function __construct(protected DashboardServicePort $service, protected FileStorageInterface $storage) {}

    public function execute(int $studentId,  int $notifPaginate = 2, int $tutorPaginate = 4)
    {
        $result = $this->service->studentHomePage($studentId, $notifPaginate, $tutorPaginate);

        $notifications = $result['notification']->through(function ($notifEntity) {
            return [
                'id' => $notifEntity->id,
                'title' => $notifEntity->title,
                'body' => $notifEntity->body,
                'data' => $notifEntity->data,
            ];
        });

        $tutors = $result['tutor_recomendation']->through(function ($tutorEntity) {
            if(!$tutorEntity->profilePhotoUrl) {
                $tutorEntity->profilePhotoUrl = $this->storage->getMedia($tutorEntity->profilePhotoUrl);
            }
            return $tutorEntity;
        });

        return [
            'user_name' => $result['me']->name,
            'warning' => $result['warning'],
            'sanction' => $result['me']->sanction,
            'session' => $result['me']->session,
            'notification' => $notifications,
            'tutor_recomendation' => $tutors,
        ];
    }
}