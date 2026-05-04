<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SuppliersController extends Controller
{
    public function manageSuppliers()
    {
        $suppliers = collect([
            (object) [
                'id' => 1,
                'name' => 'Rahim Uddin',
                'company_name' => 'Rahim Traders',
                'email' => 'rahim@mail.com',
                'phone' => '01700000001',
                'address' => 'Dhaka, Bangladesh',
                'opening_due' => 5000,
            ],
            (object) [
                'id' => 2,
                'name' => 'Karim Hasan',
                'company_name' => 'Karim Enterprise',
                'email' => 'karim@mail.com',
                'phone' => '01700000002',
                'address' => 'Chittagong, Bangladesh',
                'opening_due' => 0,
            ],
            (object) [
                'id' => 3,
                'name' => 'Global Supply Ltd',
                'company_name' => 'Global Supply Ltd',
                'email' => null,
                'phone' => '01800000000',
                'address' => 'Sylhet, Bangladesh',
                'opening_due' => 12000,
            ],
        ]);

        return view('admin.suppliers.manage-suppliers', compact('suppliers'));

    }

    public function createSuppliers()
    {

        return view('admin.suppliers.create-suppliers');
    }

    public function storeSuppliers(StoreSupplierRequest $request)
    {
        // dd($request->all());
        $validatedData = $request->validated();

        Supplier::create($validatedData);

        return response()->json(['message' => 'Supplier created successfully']);

    }
    public function editSuppliers($supplier)
    {
        $supplier = Supplier::findOrFail($supplier);

        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function updateSuppliers(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return response()->json([
            'message' => 'Supplier updated successfully'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        DB::transaction(function () use ($id, $request) {
            $category = Supplier::findOrFail($id);
            $category->status = $request->status;
            $category->save();
        });

        return response()->json([
            'message' => 'Status updated successfully'
        ]);
    }

    public function showSuppliers(Supplier $supplier)
    {
        // return view('admin.suppliers.show-suppliers', compact('supplier'));
    }

    public function data()
    {
        $query = Supplier::select(['id', 'name', 'company_name', 'email', 'phone', 'address', 'opening_balance', 'status']);

        return DataTables::of($query)

            // ->addColumn('status', function ($supplier) {
            //     return $supplier->status == 1
            //         ? '<span class="text-green-600">Active</span>'
            //         : '<span class="text-red-600">Inactive</span>';
            // })
            ->addColumn('status', function ($supplier) {
                return '
                    <div x-data="{ switcherToggle: ' . ($supplier->status == 1 ? 'true' : 'false') . ' }">
                        <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                            
                            <div class="relative">
                                <input type="checkbox"
                                    class="sr-only supplierStatusToggler"
                                    data-id="' . $supplier->id . '"
                                    x-model="switcherToggle" />
                
                                <div class="block h-6 w-11 rounded-full"
                                    :class="switcherToggle ? \'bg-green-500 dark:bg-green-500\' : \'bg-gray-200 dark:bg-white/10\'">
                                </div>
                
                                <div :class="switcherToggle ? \'translate-x-full\' : \'translate-x-0\'"
                                    class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear">
                                </div>
                            </div>
                
                            <span x-text="switcherToggle ? \'Active\' : \'Inactive\'"></span>
                        </label>
                    </div>';
            })

            ->addColumn('action', function ($supplier) {
                return '
                    <div class="flex items-center gap-2">

                        <a href="' . route('admin.suppliers.edit', $supplier->id) . '"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                            Edit
                        </a>

                        <button 
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition deleteBtn"
                            data-id="' . $supplier->id . '">
                            Delete
                        </button>

                    </div>
                ';
            })
            ->filterColumn('status', function ($query, $keyword) {
                if (strtolower($keyword) === 'active') {
                    $query->where('is_active', 1);
                } elseif (strtolower($keyword) === 'inactive') {
                    $query->where('is_active', 0);
                }
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
