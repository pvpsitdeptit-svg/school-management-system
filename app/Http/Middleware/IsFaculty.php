<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsFaculty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->get('authenticated_user');
        
        if (!$user || $user->role !== 'faculty') {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'Faculty access required'
            ], 403);
        }
        
        return $next($request);
    }
}
