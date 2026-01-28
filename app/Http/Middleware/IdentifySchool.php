<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\School;
use Illuminate\Support\Facades\Log;

class IdentifySchool
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip school identification for auth routes and super admin routes
        $excludedRoutes = ['/login', '/logout', '/super-admin'];
        
        foreach ($excludedRoutes as $route) {
            if ($request->is($route) || $request->is($route . '*')) {
                return $next($request);
            }
        }
        
        $school = null;
        
        // Try to identify school by subdomain first
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0] ?? null;
        
        if ($subdomain && $subdomain !== 'www' && $subdomain !== 'localhost') {
            $school = School::where('subdomain', $subdomain)
                ->where('status', 'active')
                ->first();
        }
        
        // Fallback to custom domain if no subdomain match
        if (!$school) {
            $school = School::where('custom_domain', $host)
                ->where('status', 'active')
                ->first();
        }
        
        // For local development, fallback to first active school
        if (!$school && app()->environment('local')) {
            $school = School::where('status', 'active')->first();
        }
        
        if ($school) {
            // Make school available globally via request
            $request->merge(['school_id' => $school->id]);
            
            // Bind school to container for easy access
            app()->instance('current_school', $school);
        }
        
        return $next($request);
    }
}
