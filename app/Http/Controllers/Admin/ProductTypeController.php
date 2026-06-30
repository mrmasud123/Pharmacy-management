<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;
use App\Models\ProductType;
use App\Services\ProductTypeService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductTypeController extends Controller
{

    public function __construct(protected ProductTypeService $productService)
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function productTypes()
    {
        return view('admin.product-types.product-types',['title' =>"Product Types"]);
    }

    public function editProductType(ProductType $productType)
    {
        $title = "Edit Product Type";
        return view('admin.product-types.form', compact('productType','title'));
    }


    public function createProductType()
    {

        return view('admin.product-types.form',['title' => "Create Product Type"]);

    }

    public function storeProductType(StoreProductTypeRequest $request)
    {

        $data = $request->validated();

        $productType = $this->productService->store($data);

        return response()->json(['message' => 'Product type created successfully', 'productType' => $productType], 201);
    }

    public function updateProductType(ProductType $productType, UpdateProductTypeRequest $request)
    {

        $data = $request->validated();

        $updatedProductType = $this->productService->update($productType, $data);

        return response()->json(['message' => 'Product type updated successfully', 'productType' => $updatedProductType], 200);

    }


    public function deleteProductType()
    {

    }

    public function updateStatus(Request $request, $id)
    {
        $category = ProductType::findOrFail($id);
        $category->status = $request->status;
        $category->save();

        return response()->json([
            'message' => 'Type updated successfully'
        ]);
    }

    public function data()
    {
        $query = ProductType::select(['id', 'name', 'code', 'status', 'slug']);

        return DataTables::of($query)

            // ->addColumn('status', function ($productType) {
            //     return $productType->status == 1
            //         ? '<span class="text-green-600">Active</span>'
            //         : '<span class="text-red-600">Inactive</span>';
            // })
            ->addColumn('status', function ($productType) {
                return '
                <div x-data="{ switcherToggle: ' . ($productType->status == 1 ? 'true' : 'false') . ' }">
                    <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">

                        <div class="relative">
                            <input type="checkbox"
                                class="sr-only productTypeStatusToggler"
                                data-id="' . $productType->id . '"
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
            ->addColumn('slug', function ($productType) {
                return '
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-amber-800 bg-amber-100 rounded-md">
                        <i class="fas fa-link text-amber-600"></i>
                        ' . e($productType->slug) . '
                    </span>
                ';
            })

            ->addColumn('action', function ($productType) {
                return '
                    <div class="flex items-center gap-2">

                        <a href="' . route('admin.product.type.edit', $productType->id) . '"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                            Edit
                        </a>

                        <button
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition deleteBtn"
                            data-id="' . $productType->id . '">
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

            ->rawColumns(['status', 'slug', 'action'])
            ->make(true);
    }
}
