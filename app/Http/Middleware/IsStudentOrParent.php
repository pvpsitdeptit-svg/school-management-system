<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStudentOrParent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->get('authenticated_user');
        
        if (!$user || !in_array($user->role, ['student', 'parent'])) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'Student or Parent access required'
            ], 403);
        }
        
        return $next($request);
    }
}
