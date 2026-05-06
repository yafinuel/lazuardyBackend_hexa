<?php

namespace App\Domains\Review\Infrastructure\Repository;

use App\Domains\Review\Ports\ReviewRepositoryInterface;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function create(int $studentId, array $data)
    {
        $student = Student::where('user_id', $studentId)->firstOrFail();
        $student->reviews()->create([
            'tutor_id' => $data['tutor_id'],
            'rate' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function findById(int $id)
    {
        // Implement the logic to find a review by its ID using Eloquent
    }

    public function findWithFilters(array $filters, int $pagination = 10): LengthAwarePaginator
    {
        $query = Review::with('student.user', 'tutor.user');

        $query->when(isset($filters['tutor_id']), function ($q) use ($filters) {
            $q->where('tutor_id', $filters['tutor_id']);
        });
        $query->when(isset($filters['student_id']), function ($q) use ($filters) {
            $q->where('student_id', $filters['student_id']);
        });
        $query->when(isset($filters['min_rating']), function ($q) use ($filters) {
            $q->where('rate', '>=', $filters['min_rating']);
        });
        $query->when(isset($filters['max_rating']), function ($q) use ($filters) {
            $q->where('rate', '<=', $filters['max_rating']);
        });
        
        return $query->paginate($pagination);
    }

    public function getAvgRatingForTutor(int $tutorId): float
    {
        return (float) (Review::where('tutor_id', $tutorId)->avg('rate') ?? 0);
    }

    public function update(int $id, array $data)
    {
        // Implement the logic to update a review by its ID using Eloquent
    }

    public function delete(int $id)
    {
        // Implement the logic to delete a review by its ID using Eloquent
    }
}