<?php

use App\Http\Middleware\EnsureDatabaseConnection;
use App\Http\Middleware\LogUserActions;
use App\Http\Middleware\RemoveHeadersMiddleware;
use App\Http\Middleware\TrackUserActivity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/status',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
            ThrottleRequests::class,
            SubstituteBindings::class,
        ]);

        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        $middleware->web(append: [
            TrackUserActivity::class,
            LogUserActions::class,
        ]);

        $middleware->append(RemoveHeadersMiddleware::class);
        $middleware->append(EnsureDatabaseConnection::class);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
