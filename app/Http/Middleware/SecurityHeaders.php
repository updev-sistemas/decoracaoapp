<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;

    class SecurityHeaders
    {
        /**
         * Intercepta a resposta HTTP e adiciona cabeçalhos de segurança.
         */
        public function handle(Request $request, Closure $next): Response
        {
            /** @var Response $response */
            $response = $next($request);

            // Impede que a resposta seja embutida em <frame> ou <iframe> (proteção contra Clickjacking)
            $response->headers->set('X-Frame-Options', 'DENY');

            // Impede que o navegador adivinhe o MIME-type do arquivo enviado
            $response->headers->set('X-Content-Type-Options', 'nosniff');

            // Ativa a proteção XSS do navegador
            $response->headers->set('X-XSS-Protection', '1; mode=block');

            // Controla quais informações de referência são enviadas nos links
            $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');

            return $response;
        }
    }
