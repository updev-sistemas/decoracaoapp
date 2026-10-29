<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    /**
     * Construtor da classe.
     * Aplica o middleware 'auth:api' para proteger as rotas me, logout e refresh.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Retorna a instância do Guard configurada para JWT com o tipo correto para o Intelephense.
     *
     * @return JWTGuard
     */
    protected function guard(): JWTGuard
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');
        return $guard;
    }

    /**
     * Autentica o utilizador e devolve o token JWT.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        // O Intelephense agora reconhece o método 'attempt' através do método guard()
        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Credenciais inválidas. Verifique o e-mail e a senha.'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Retorna os dados do utilizador autenticado.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => $this->guard()->user()
        ]);
    }

    /**
     * Invalida o token atual e gera um novo token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        // O Intelephense agora reconhece o método 'refresh'
        $newToken = $this->guard()->refresh();

        return $this->respondWithToken($newToken);
    }

    /**
     * Realiza o logout do utilizador (invalida o token JWT).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // O Intelephense agora reconhece o método 'logout'
        $this->guard()->logout();

        return response()->json([
            'status'  => 'success',
            'message' => 'Sessão encerrada com sucesso.'
        ]);
    }

    /**
     * Estrutura auxiliar para padronizar a resposta contendo o token JWT.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'status'       => 'success',
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->guard()->factory()->getTTL() * 60
        ]);
    }
}
