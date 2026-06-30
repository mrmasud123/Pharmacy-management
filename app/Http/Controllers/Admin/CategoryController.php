<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Yajra\DataTables\Facades\DataTables;
class CategoryController extends Controller
{
    public function categories()
    {


        return view('admin.category.categories', ['title' =>"Categories"]);
    }

    public function createCategory()
    {
        return view('admin.category.create-category', ['title' => "Create Category"]);
    }

    public function editCategory(Category $category)
    {
        $title = "Edit Category";
        return view('admin.category.edit-category', compact('category', 'title'));
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::create([
            'name' => $data['category_name'],
            'code' => $data['category_code'],
            'is_active' => $data['category_status'],
        ]);

        if ($request->hasFile('category_image')) {
            $category->addMediaFromRequest('category_image')
                ->toMediaCollection('categories', 'public');
        }

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
        ]);
    }


    public function updateCategory(
        UpdateCategoryRequest $request,
        Category $category,
        CategoryService $categoryService
    ) {
        $categoryService->update($category, $request->validated());

        return response()->json([
            'message' => 'Category updated successfully.'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = $request->status;
        $category->save();

        return response()->json([
            'message' => 'Status updated successfully'
        ]);
    }

    public function data()
    {
        $query = Category::select(['id', 'name', 'is_active']);

        return DataTables::of($query)

            ->addColumn('status', function ($category) {
                return '
            <div x-data="{ switcherToggle: ' . ($category->is_active == 1 ? 'true' : 'false') . ' }">
                <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">

                    <div class="relative">
                        <input type="checkbox"
                            class="sr-only categoryStatusToggler"
                            data-id="' . $category->id . '"
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
            ->addColumn('image', function ($category) {
                $url = $category->getFirstMediaUrl('categories');
                return '<img class="w-20 h-20 object-cover rounded" src="' . $url . '" />';
            })

            ->addColumn('action', function ($category) {
                return '
                    <div class="flex items-center gap-2">

                        <!-- Edit Link -->
                        <a href="' . route('admin.categories.edit', $category->id) . '"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                            Edit
                        </a>

                        <!-- Delete Button -->
                        <button
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition deleteBtn"
                            data-id="' . $category->id . '">
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

            ->rawColumns(['status', 'image', 'action'])
            ->make(true);
    }
}
