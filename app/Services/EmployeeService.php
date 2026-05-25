<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * Create Employee
     */
    public function store(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
 

            $employee = Employee::create([

                'name'            => $data['name'],

                'email'           => $data['email'] ?? null,

                'phone'           => $data['phone'],

                'employee_code'   => $data['employee_code'],

                'designation'     => $data['designation'] ?? null,

                'join_date'       => $data['join_date'] ?? null,

                'dob'             => $data['dob'] ?? null,

                'gender'          => $data['gender'] ?? null,

                'address'         => $data['address'] ?? null,

                'basic_salary'    => $data['basic_salary'] ?? 0,

                'allowance'       => $data['allowance'] ?? 0,

                'deduction'       => $data['deduction'] ?? 0,

                'is_active'       => $data['is_active'],

                'password'        => $data['password'],
            ]);
            return $employee;
        });
    }

 
    public function update(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
 

            $employee->update([

                'name'            => $data['name'],

                'email'           => $data['email'] ?? null,

                'phone'           => $data['phone'],

                'designation'     => $data['designation'] ?? null,

                'join_date'       => $data['join_date'] ?? null,

                'dob'             => $data['dob'] ?? null,

                'gender'          => $data['gender'] ?? null,

                'address'         => $data['address'] ?? null,

                'basic_salary'    => $data['basic_salary'] ?? 0,

                'allowance'       => $data['allowance'] ?? 0,

                'deduction'       => $data['deduction'] ?? 0,

                'is_active'       => $data['is_active'],
            ]);
 

            if (!empty($data['password'])) {

                $employee->update([
                    'password' => $data['password']
                ]);
            }

            return $employee;
        });
    }
}