<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'category_id'        => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'name'               => ['sometimes', 'required', 'string', 'max:400'],
            'description'        => ['sometimes', 'required', 'string', 'max:255'],
            'photo_ref'          => ['sometimes', 'nullable', 'string', 'max:40'],
            'original_price'     => ['sometimes', 'required', 'numeric', 'between:0,999999.99'],
            'markup'             => ['sometimes', 'nullable', 'integer', 'min:0'],
            'available_for_rent' => ['sometimes', 'nullable', 'boolean'],
        ];
    }
}
