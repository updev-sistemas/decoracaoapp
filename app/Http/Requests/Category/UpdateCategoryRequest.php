<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'section_id'  => ['sometimes', 'required', 'integer', 'exists:sections,id'],
            'name'        => ['sometimes', 'required', 'string', 'max:50'],
            'description' => ['sometimes', 'required', 'string', 'max:200'],
        ];
    }
}
