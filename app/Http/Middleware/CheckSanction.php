<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSanction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->sanction){
            $sanctionExpiry = Carbon::parse($user->sanction);

            if ($sanctionExpiry->isFuture()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Akun Anda sedang disanksi hingga ' . $sanctionExpiry->toDateTimeString(),
                ], 403);
            } else {
                // Jika sanksi sudah lewat, hapus sanksi
                $user->sanction = null;
                $user->save();
            }
        }
        return $next($request);
    }
}
