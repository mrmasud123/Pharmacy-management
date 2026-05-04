<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [ 
            'name' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'code')->ignore($productId),
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'barcode')->ignore($productId),
            ],

            'brand_id' => ['required', 'exists:brands,id'],
            'product_type_id' => ['required', 'exists:product_types,id'],
            'category_id' => ['required', 'exists:categories,id'],
    
            'alert_quantity' => ['nullable', 'integer', 'min:0'],
 
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'  
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'code.required' => 'Product code is required.',
            'code.unique' => 'This product code already exists.',
            'barcode.unique' => 'This barcode already exists.',
            'alert_quantity.min' => 'Alert quantity cannot be negative.',
            'image.mimes' => 'Image must be jpg, jpeg, png, or webp.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}