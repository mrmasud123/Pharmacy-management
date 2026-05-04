<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_name' => ['required','string','unique:categories,name'],
            'category_code' => ['required','string', 'unique:categories,code'],
            'category_status' => ['required','boolean','in:1,0'],
            'category_image' => ['nullable', 'image','mimes:jpg,jpeg,png,webp', 'max:2048']
        ];
    }
    
    public function messages(): array
    {
        return [
            'category_name.required' => 'Category name is required',
            'category_name.unique' => 'Category name must be unique',
            'category_code.required' => 'Category code is required',
            'category_code.unique' => 'Category code must be unique',
            'category_image.image'    => 'Uploaded file must be an image'
        ];
    }
}
