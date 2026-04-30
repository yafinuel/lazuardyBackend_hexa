<?php

namespace App\Http\Middleware;

use App\Domains\Tutor\Actions\GetTutorFileByUserIdAction;
use App\Shared\Enums\TutorStatusEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifiedTutor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, GetTutorFileByUserIdAction $getTutorAction): Response
    {
        $user = $request->user();
        $tutor = $getTutorAction->execute($user->id);

        if ($tutor->status == TutorStatusEnum::PENDING->value) {
            return response()->json([
                'turor_status' => $tutor->status,
                'message' => 'Your tutor account is still pending verification. Please wait for the admin to verify your account.',
            ], 403);
        } else if ($tutor->status == TutorStatusEnum::REJECTED->value) {
            return response()->json([
                'turor_status' => $tutor->status,
                'message' => 'Your tutor account has been rejected. Please contact support for more information.',
            ], 403);
        } else if ($tutor->status == TutorStatusEnum::VERIFIED->value) {
            return $next($request);
        } else {
            return response()->json([
                'turor_status' => $tutor->status,
                'message' => 'Your tutor account has an unknown status. Please contact support for more information.',
            ], 403);
        }
    }
}
