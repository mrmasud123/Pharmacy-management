<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{

    public function __construct(protected BrandService $brandService)
    {
        $this->brandService = $brandService;
    }


    public function brands()
    {
       $title= "Brands";
        return view('admin.brands.manage-brand', compact('title'));
    }

    public function createBrand()
    {
        return view('admin.brands.form',['title'=>'Create Brand']);
    }

    public function editBrand(Brand $brand)
    {
        return view('admin.brands.form', compact('brand',['title'=>'Edit Brand']));
    }

    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();

        $brand = $this->brandService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully!',
            'data' => $brand
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $category = Brand::findOrFail($id);
        $category->status = $request->status;
        $category->save();

        return response()->json([
            'message' => 'Status updated successfully'
        ]);
    }
    public function data()
    {
        $query = Brand::select(['id', 'name', 'code', 'status', 'created_by']);

        return DataTables::of($query)

            // ->addColumn('status', function ($brand) {
            //     return $brand->status == 1
            //         ? '<span class="text-green-600">Active</span>'
            //         : '<span class="text-red-600">Inactive</span>';
            // })

            ->addColumn('status', function ($brand) {
                return '
                    <div x-data="{ switcherToggle: ' . ($brand->status == 1 ? 'true' : 'false') . ' }">
                        <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">

                            <div class="relative">
                                <input type="checkbox"
                                    class="sr-only brandStatusToggler"
                                    data-id="' . $brand->id . '"
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

            ->addColumn('action', function ($brand) {
                return '
                    <div class="flex items-center gap-2">

                        <a href="' . route('admin.brand.edit', $brand->id) . '"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                            Edit
                        </a>

                        <button
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition deleteBtn"
                            data-id="' . $brand->id . '">
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
