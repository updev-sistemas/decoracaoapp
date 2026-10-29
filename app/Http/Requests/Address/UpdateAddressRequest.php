<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => ['sometimes', 'required', 'integer', 'exists:warehouses,id'],
            'sequence'     => ['sometimes', 'required', 'integer', 'min:1'],
            'layover'      => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
