<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'             => \App\Http\Middleware\EnsureRole::class,
            'active'           => \App\Http\Middleware\EnsureActiveUser::class,
            'active-period'    => \App\Http\Middleware\SetActiveAcademicPeriod::class,
            'password-changed' => \App\Http\Middleware\EnsurePasswordChanged::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            if (! $user) {
                return '/';
            }
            return route($user->dashboardRoute());
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Tu sesión ha expirado. Por favor inicia sesión de nuevo.']);
        });
    })->create();
