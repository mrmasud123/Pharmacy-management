<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee')
            ? $this->route('employee')->id
            : null;

        return [

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:150',
                Rule::unique('customers', 'email')->ignore($employeeId),
            ],

            'phone' => [
                'required',
                'string',
                'max:11',
                'min:11',
                'starts_with:013,014,015,016,017,018,019',
                Rule::unique('customers', 'phone')->ignore($employeeId),
            ],

            'designation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'join_date' => [
                'nullable',
                'date',
            ],

            'dob' => [
                'nullable',
                'date',
                'before:today',
            ],


            'gender' => [
                'nullable',
                Rule::in(['Male', 'Female', 'Other']),
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],


            'basic_salary' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'allowance' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            'is_active' => [
                'required',
                'boolean',
            ],

            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:6',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => 'Employee name is required.',

            'phone.required' => 'Phone number is required.',
            'phone.unique'   => 'This phone number already exists.',

            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email already exists.',

            'dob.before' => 'Date of birth must be before today.',

            'password.required' => 'Password is required for new employee.',
            'password.min'      => 'Password must be at least 6 characters.',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([

            'name' => $this->name
                ? trim($this->name)
                : null,

            'email' => $this->email
                ? strtolower(trim($this->email))
                : null,

            'phone' => $this->phone
                ? trim($this->phone)
                : null,

            'designation' => $this->designation
                ? trim($this->designation)
                : null,

            'address' => $this->address
                ? trim($this->address)
                : null,

            'basic_salary' => $this->basic_salary ?: 0,
            'allowance'    => $this->allowance ?: 0,
            'deduction'    => $this->deduction ?: 0,

            'is_active' => $this->is_active ?? 1,
        ]);
    }


    public function validatedData(): array
    {
        $data = $this->validated();

        if ($this->isMethod('post')) {

            $lastEmployee = \App\Models\Employee::latest('id')->first();

            $nextId = $lastEmployee
                ? $lastEmployee->id + 1
                : 1;

            $data['employee_code'] = 'EMP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        }



        if (!empty($data['password'])) {

            $data['password'] = bcrypt($data['password']);

        } else {

            unset($data['password']);
        }

        return $data;
    }
}
