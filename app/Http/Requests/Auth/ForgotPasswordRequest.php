<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'Insira um e-mail válido.',
            'email.exists'   => 'Não encontramos nenhum utilizador com este endereço de e-mail.',
        ];
    }
}
