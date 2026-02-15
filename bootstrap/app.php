<?php

use App\Core\Shared\Domain\DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $exceptions->render(function (DomainException $e) {
            return response()->json([
                'type' => 'DOMAIN_ERROR',
                'domain' => $e->domain,
                'code' => $e->errorCode,
                'app_code' => $e->appCode,
                'message' => $e->getMessage(),
            ], 409);
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'type' => 'NOT_FOUND',
                'message' => 'Recurso não encontrado',
            ], 404);
        });
    })->create();
