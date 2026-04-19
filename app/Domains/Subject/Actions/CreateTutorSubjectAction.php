<?php

namespace App\Domains\Subject\Actions;

use App\Domains\Subject\Ports\SubjectRepositoryInterface;

class CreateTutorSubjectAction
{
    public function __construct(protected SubjectRepositoryInterface $repository) {}

    public function execute(array $data): bool
    {
        $tutorId = $data['tutor_id'];
        $subjectId = $data['subject_id'];
        return $this->repository->createTutorSubject($tutorId, $subjectId);
    }
}