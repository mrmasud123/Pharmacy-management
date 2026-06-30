<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{

    public function customers(){
        return view('admin.customers.customers', ['title' => "Customers"]);
    }
    public function search(Request $request)
    {
        $query = $request->q;

        $customers = Customer::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'phone']);

        return response()->json([
            'data' => $customers
        ]);
    }

    public function storeCustomer(){

    }

    public function createCustomer(){
        return view('admin.customers.create-customer', ['title' => "Create customer"]);
    }

    public function editCustomer(Customer $customer)
    {
        $title = "Edit customer";
        return view('admin.customers.edit-customer', compact('customer','title'));
    }

    public function updateCustomer(){
        return "HEllo";
    }
    public function rolePermissionMapping(){

        return view('admin.employees.employee-with-role-permission',['title' => "Role Permission Mapping"] );
    }
    public function invoice(Request $request)
    {
        $query = $request->q;

        $invoices = Sale::query()
            ->when($query, function ($q) use ($query) {
                $q->where('invoice_no', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'invoice_no']);

        return response()->json([
            'data' => $invoices
        ]);
    }

    public function assignEmployeeRole(User $user){
        $roles = Role::select('id', 'name')->get();

        $user = User::select('id', 'name', 'email')
            ->with('roles:id,name')
            ->findOrFail($user->id);
        $title= "Assign Employee Role";
        return view('admin.employees.role-permission-mapping', compact('title','roles', 'user'));
    }

    public function storeMapping(Request $request)
    {
        // return $request->input();
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'roles' => 'required|array',
        ]);

        $employee = User::findOrFail($request->employee_id);

        $roleNames = Role::whereIn('id', $request->roles)
            ->pluck('name')
            ->toArray();

        $employee->syncRoles($roleNames);

        return redirect()->back()->with('success', 'Role mapping saved successfully.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:customers,phone|min:11|max:11|starts_with:013,014,015,016,017,018,019',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer created successfully',
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
            ]
        ]);
    }

    public function userWithRolesPermissionData()
    {
        return DataTables::of(User::query())

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

    public function data()
    {
        return DataTables::of(Customer::query())
            ->addColumn('action', function ($customer) {

                return '
                    <div class="flex  gap-2">
                        <a href="'.route('admin.customers.edit', $customer->id).'" class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded">
                            Edit
                        </a>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
