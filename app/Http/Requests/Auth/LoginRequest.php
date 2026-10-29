<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determina se o utilizador está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação aplicadas aos dados de entrada.
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'O campo e-mail é obrigatório.',
            'email.email'       => 'Insira um endereço de e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min'      => 'A senha deve ter pelo menos 6 caracteres.',
        ];
    }
}
