<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'category_id'        => ['required', 'integer', 'exists:categories,id'],
            'name'               => ['required', 'string', 'max:400'],
            'description'        => ['required', 'string', 'max:255'],
            'photo_ref'          => ['nullable', 'string', 'max:40'],
            'original_price'     => ['required', 'numeric', 'between:0,999999.99'],
            'markup'             => ['nullable', 'integer', 'min:0'],
            'available_for_rent' => ['nullable', 'boolean'],
        ];
    }
}
