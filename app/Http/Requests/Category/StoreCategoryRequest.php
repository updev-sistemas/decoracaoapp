<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'section_id'  => ['required', 'integer', 'exists:sections,id'],
            'name'        => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:200'],
        ];
    }
}
