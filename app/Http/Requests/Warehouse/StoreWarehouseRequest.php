<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'local' => ['required', 'string', 'max:255'],
        ];
    }
}
