<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateSupplierRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->supplier),],
            // 'email' => ['nullable', 'email', 'max:255', new UniqueEmailAcrossDB],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('suppliers', 'email')->ignore($this->supplier)],
            
            'phone' => [
                'required',
                'string',
                'size:11',
                'regex:/^01[3-9][0-9]{8}$/',
                Rule::unique('suppliers', 'phone')->ignore($this->supplier),
            ],
            'opening_balance' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }
}
