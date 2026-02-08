<?php

use App\Exceptions\AppLogicException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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
        $exceptions->render(function (AppLogicException $e, Request $_) {
            return response()->json([
                'errors' => [
                    [
                        'code' => $e->getCode(),
                        'title' => $e->getMessage(),
                    ],
                ],
            ], $e->getCode());
        });
    })->create();
