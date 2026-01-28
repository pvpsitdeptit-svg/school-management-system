<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\IdentifySchool::class,
        ]);
        
        $middleware->api(append: [
            \App\Http\Middleware\IdentifySchool::class,
        ]);
        
        // Register Firebase authentication middleware
        $middleware->alias([
            'auth.firebase' => \App\Http\Middleware\AuthenticateFirebase::class,
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'faculty' => \App\Http\Middleware\IsFaculty::class,
            'student.or.parent' => \App\Http\Middleware\IsStudentOrParent::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
