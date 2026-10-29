<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Intercepta a requisição e garante que o cabeçalho 'Accept' seja 'application/json'.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Força o cabeçalho 'Accept' da requisição para 'application/json'
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
