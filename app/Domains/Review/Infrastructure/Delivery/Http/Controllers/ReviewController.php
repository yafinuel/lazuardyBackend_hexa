<?php

namespace App\Domains\Review\Infrastructure\Delivery\Http\Controllers;

use App\Domains\Review\Actions\CreateReviewAction;
use App\Domains\Review\Actions\ReviewFromStudentPageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function getTutorReviewsAsTutor(Request $request, ReviewFromStudentPageAction $action)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:students,user_id',
            'min_rating' => 'nullable|integer|min:1|max:5',
            'max_rating' => 'nullable|integer|min:1|max:5',
            'pagination' => 'nullable|integer|min:1',
        ]);

        $tutorId = $request->user()->id;
        $result = $action->execute($tutorId, $data, $data['pagination'] ?? 10);

        return response()->json([
            'status' => 'success',
            'avg_rating' => $result['avg_rating'],
            'data' => $result['reviews']
        ]);
    }

    public function createReview(Request $request, CreateReviewAction $action)
    {
        $data = $request->validate([
            'tutor_id' => 'required|integer|exists:tutors,user_id',
            'rating'   => 'required|numeric|between:0,5.0',
            'comment'  => 'nullable|string',
        ]);

        $studentId = $request->user()->id;
        $action->execute($studentId, $data);

        return response()->json(['status' => 'success', 'message' => 'Review created successfully']);
    }
}