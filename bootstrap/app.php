<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\SecurityHeaders;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Adiciona o middleware globalmente no início da pilha HTTP
        $middleware->prepend(ForceJsonResponse::class);

        // Segurança de Manipulação de Headers
        $middleware->prepend(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // $exceptions->shouldRenderJsonWhen(
        //     fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        // );
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            return true;
        });

        // Personaliza a resposta para rotas inexistentes (404)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'A rota solicitada não foi encontrada.'
            ], 404);
        });
    })
    ->create();
