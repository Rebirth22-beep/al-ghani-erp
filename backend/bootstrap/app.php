<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvoiceAlreadyPostedException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\ForceJsonResponse::class);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (InsufficientStockException $e, Request $request) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        });

        $exceptions->render(function (InvoiceAlreadyPostedException $e, Request $request) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        });
    })->create();
