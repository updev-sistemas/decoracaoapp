<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:200'],
        ];
    }
}
