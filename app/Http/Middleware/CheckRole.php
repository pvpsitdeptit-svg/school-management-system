<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect based on user's actual role
            switch ($user->role) {
                case 'super_admin':
                    return redirect('/super-admin/dashboard');
                case 'school_admin':
                    return redirect('/dashboard');
                case 'faculty':
                    return redirect('/faculty/dashboard');
                case 'student':
                    return redirect('/student/dashboard');
                default:
                    return redirect('/login');
            }
        }

        return $next($request);
    }
}
