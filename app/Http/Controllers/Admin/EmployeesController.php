<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;


class EmployeesController extends Controller
{
    public function employees()
    {
        return view('admin.employees.employees');
    }

    public function createEmployee()
    {
        return view('admin.employees.create-employee');
    }

    public function storeEmployee(StoreEmployeeRequest $request, EmployeeService $service)
    {
        $data=$request->validatedData();
        
        $service->store($data);
        
        return redirect()
        ->route('admin.employees.manage')
        ->with('success', 'Employee created successfully.');
    }
    
    public function assignEmployeeRole(Employee $employee){
        $roles = Role::select('id', 'name')->get();
        
        $employee = Employee::select('id', 'name', 'email')
        ->with('roles:id,name')
        ->findOrFail($employee->id);
        
        return view('admin.employees.role-permission-mapping', compact('roles', 'employee'));
    }
    
    public function storeMapping(Request $request)
    {
        // return $request->input();
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'roles' => 'required|array',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
 
        $roleNames = Role::whereIn('id', $request->roles)
            ->pluck('name')
            ->toArray();

        $employee->syncRoles($roleNames);

        return redirect()->back()->with('success', 'Role mapping saved successfully.');
    }

    public function editEmployee(int $id)
    {
        $employee= Employee::where('id', $id)->get();
        return view('admin.employees.edit-employee', ['employee'=>$employee]);
    }

    public function updateEmployee(Request $request, $employee)
    {
        //
    }

    public function deleteEmployee($employee)
    {
        //
    }

  

    public function updateStatus($id)
    {
        //
    }

    
    public function data()
    {
        return DataTables::of(Employee::query())

     
            ->addColumn('action', function ($employee) {

                return '
                    <a href="'.route('admin.employees.edit', $employee->id).'"
                    class="text-blue-600 mr-2">
                    Edit
                    </a>

                    <button class="text-red-600 deleteBtn"
                            data-id="'.$employee->id.'">
                        Delete
                    </button>
                ';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
    
    
    public function rolePermissionMapping(){
       
        return view('admin.employees.employee-with-role-permission' );   
    }
    
    public function employeesWithRolesPermissionData()
    {
        return DataTables::of(Employee::query())

            // ROLE COLUMN (Spatie)
            ->addColumn('role', function ($employee) {

                $roles = $employee->getRoleNames();

                if ($roles->isEmpty()) {
                    return '<span class="text-gray-500">No Role</span>';
                }

                return $roles->map(function ($role) {
                    return '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-600 mr-1">'
                        . $role .
                    '</span>';
                })->implode(' ');
            })

            // PERMISSIONS COLUMN (Spatie)
            ->addColumn('permissions', function ($employee) {

                $perms = $employee->getAllPermissions();

                if ($perms->isEmpty()) {
                    return '<span class="text-gray-500">No permissions</span>';
                }

                $html = $perms->take(3)->map(function ($p) {
                    return '<span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-600 mr-1">'
                        . $p->name .
                    '</span>';
                })->implode(' ');

                if ($perms->count() > 3) {
                    $html .= '<span class="text-xs text-gray-500 ml-1">+'
                        . ($perms->count() - 3) . ' more</span>';
                }

                return $html;
            })

            // ACTION COLUMN
            ->addColumn('action', function ($employee) {

                return '
                    <a href="'.route('admin.assign.role', $employee->id).'"
                    class="text-blue-600 mr-2">
                    Assign Role
                    </a>
                ';
            })

            ->rawColumns(['role', 'permissions', 'action'])
            ->make(true);
    }
}