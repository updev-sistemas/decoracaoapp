<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name'  => ['sometimes', 'required', 'string', 'max:255'],
            'local' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
