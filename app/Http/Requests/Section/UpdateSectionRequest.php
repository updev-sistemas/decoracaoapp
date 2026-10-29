<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'required', 'string', 'max:50'],
            'description' => ['sometimes', 'required', 'string', 'max:200'],
        ];
    }
}
