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
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (\App\Shared\Domain\Exception\NotFoundException $e) {
        return response()->json(['error' => $e->getMessage()], 404);
    });

    $exceptions->render(function (\App\Shared\Domain\Exception\BusinessRuleViolationException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    });

    $exceptions->render(function (\App\Shared\Domain\Exception\UnauthorizedException $e) {
        return response()->json(['error' => $e->getMessage()], 401);
    });

    $exceptions->render(function (\InvalidArgumentException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    });
})->create();
