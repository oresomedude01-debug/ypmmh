<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\CacheControlMiddleware::class,
        ]);

        // Register Spatie permission middleware aliases so route middleware
        // like ->middleware(['role:Admin']) resolve correctly.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'ensure_active' => \App\Http\Middleware\EnsureActiveAccount::class,
            'force_password_change' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);

        // Exclude Paystack webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'webhooks/paystack',
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // CSRF / session expiry → silently redirect to login with a friendly message
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please log in again.'], 419);
            }
            return redirect()->route('login')
                ->with('status', 'Your session expired. Please log in again to continue.');
        });

        // Unauthenticated → redirect to login instead of throwing
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'))
                ->with('status', 'Please log in to continue.');
        });

        // Custom error pages in all environments
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response) {
            $code = $response->getStatusCode();

            $errorViews = [
                401 => 'errors.401',
                403 => 'errors.403',
                404 => 'errors.404',
                419 => 'errors.419',
                500 => 'errors.500',
                503 => 'errors.503',
            ];

            if (isset($errorViews[$code]) && view()->exists($errorViews[$code])) {
                return response()->view($errorViews[$code], [], $code);
            }

            return $response;
        });
    })->create();
