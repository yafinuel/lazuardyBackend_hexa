<?php

namespace App\Http\Middleware;

use App\Domains\Tutor\Actions\GetTutorByIdAction;
use App\Shared\Enums\TutorStatusEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifiedTutor
{

    public function __construct(
        protected GetTutorByIdAction $getTutorByIdAction,
    ) {}
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $tutor = $this->getTutorByIdAction->execute($user->id);

        if ($tutor->status == TutorStatusEnum::PENDING) {
            return response()->json([
                'turor_status' => $tutor->status,
                'message' => 'Your tutor account is still pending verification. Please wait for the admin to verify your account.',
            ], 403);
        } else if ($tutor->status == TutorStatusEnum::REJECTED) {
            return response()->json([
                'turor_status' => $tutor->status,
                'message' => 'Your tutor account has been rejected. Please contact support for more information.',
            ], 403);
        } else if ($tutor->status == TutorStatusEnum::VERIFIED) {
            return $next($request);
        } else {
            return response()->json([
                'turor_status' => $tutor->status,
                'message' => 'Your tutor account has an unknown status. Please contact support for more information.',
            ], 403);
        }
    }
}
