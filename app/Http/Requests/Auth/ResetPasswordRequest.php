<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'    => 'required|string',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required'     => 'O token de redefinição é obrigatório.',
            'email.required'     => 'O campo e-mail é obrigatório.',
            'email.exists'       => 'O e-mail informado não está cadastrado.',
            'password.required'  => 'A nova senha é obrigatória.',
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ];
    }
}
