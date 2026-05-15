<?php

namespace App\Domains\Review\Infrastructure\Repository;

use App\Domains\Review\Entities\ReviewEntity;
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
            'rate' => $data['rate'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function findById(int $id): ReviewEntity
    {
        $review = Review::findOrFail($id);

        return new ReviewEntity(
            id: $review->id,
            studentId: $review->student_id,
            tutorId: $review->tutor_id,
            rate: $review->rate,
            comment: $review->comment,
            createdAt: $review->created_at,
            updatedAt: $review->updated_at
        );
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
        $review = Review::findOrFail($id);
        $review->update($data);
    }

    public function delete(int $id): bool
    {
        return Review::where('id', $id)->delete();
    }
}