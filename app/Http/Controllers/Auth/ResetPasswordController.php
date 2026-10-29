<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * Envia o e-mail com o token de redefinição de senha.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // O Password Broker envia a notificação definida no Model User
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status'  => 'success',
                'message' => 'E-mail de redefinição de senha enviado com sucesso.'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Não foi possível enviar o e-mail de redefinição.'
        ], 400);
    }

    /**
     * Executa a redefinição da senha do utilizador.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Senha redefinida com sucesso. Já pode efetuar o login com a nova senha.'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'O token é inválido ou expirou. Por favor, solicite uma nova redefinição.'
        ], 400);
    }
}
