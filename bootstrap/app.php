<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'O registro solicitado não foi encontrado.',
            ], 404);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A rota solicitada não existe.'
                ], 404);
            }
        });

        $exceptions->render(function (\Throwable $e, Request $request) {

            if ($request->is('api/*')) {
                $payload = [
                    'status' => 'error',
                    'message' => 'Ocorreu um erro interno inesperado no servidor.',
                ];

                if (config('app.debug')) {
                    $payload['debug'] = $e->getMessage();
                    $payload['file'] = $e->getFile();
                    $payload['line'] = $e->getLine();
                }

                return response()->json($payload, 500);
            }
        });
    })->create();
