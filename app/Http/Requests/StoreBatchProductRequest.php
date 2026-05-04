<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'supplier_id'     => ['nullable', 'exists:suppliers,id'],
            'batch_no'        => ['nullable', 'string', 'max:100'],
            'expire_date'     => ['required', 'date', 'after:today'],
            'quantity'        => ['required', 'integer', 'min:1'],
            'unit_id'         => ['nullable', 'exists:units,id'],
            'purchase_price'  => ['required', 'numeric', 'min:0'],
            'sales_price'     => ['required', 'numeric', 'gte:purchase_price'],
        ];
    }

    public function messages(): array
    {
        return [
            'expire_date.after' => 'Expiry date must be in the future.',
            'sales_price.gte'   => 'Sales price must be greater than or equal to purchase price.',
        ];
    }
}