<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if(!$request->user()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',
            ], 401);
        }
        
        $userRole = $request->user()->role->value;
        
        if ($userRole !== $role) {
            return response()->json([
                'status' => 'failed',
                'message' => "This page is Forbidden for {$userRole}",
            ], 403);
        }

        return $next($request);
    }
}
