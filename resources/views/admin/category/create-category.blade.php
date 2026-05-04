@extends('layouts.app')


@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/category.js'])
@endsection
@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Categories', 'link'=> route('admin.categories.manage')],
    ['name' => 'Create', 'link'=> '#']
]" />

<div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Add New Category
            </h2>
            <p class="text-sm text-gray-500">
                Fill in the details below to create a new category.
            </p>
        </div>
        
        <a href="{{ route('admin.categories.manage') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">View Categories</a>
    </div>

    <form method="POST" action="{{ route('admin.category.store') }}" class="space-y-5" id="createCategoryForm" enctype="multipart/form-data"> 
        @csrf
        <div class="flex w-full gap-4">
            <!-- Name -->
            <div class="flex-1">
                <label class="text-sm text-gray-700 dark:text-gray-200">Category Name</label>
                <input type="text"
                name="category_name"
                    placeholder="Enter category name"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
            </div>

            <!-- Code -->
            <div class="flex-1">
                <label class="text-sm text-gray-700 dark:text-gray-200">Category Code</label>
                <input type="text"
                name="category_code"
                    placeholder="Enter category code"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
            </div>
        </div>

        <div class="flex w-full gap-4">
            <!-- Image -->
             
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Category Image
                </label>
                
                <!-- File Input -->
                <input type="file"
                    id="imageInput"
                    accept="image/*"
                    name="category_image"
                    class="w-full border rounded-lg px-3 py-2">

                <small class="text-gray-400">Image size 300 x 250 px</small>

                <!-- Preview + Remove -->
                <div class="mb-3 relative w-75">
                    <img id="imagePreview"
                        src="https://placehold.co/300x250"
                        class="w-75 h-62.5 object-cover rounded-lg border">

                    <!-- Remove Button -->
                    <button type="button"
                            id="removeImage"
                            class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded shadow">
                        Remove
                    </button>
                </div>

                
            </div>
            <!-- Status -->
            <div class="flex-1">
                
                <label class="text-sm text-gray-700 dark:text-gray-200">Status</label>
                <select name="category_status" class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
                    <option value="0" disabled selected>Choose category status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
        

        <!-- Buttons -->
        <div class="flex justify-between pt-4">

            <a href="{{ route('admin.categories.manage') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg text-sm">
                Cancel
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Save
            </button>

        </div>

    </form>

</div>

@endsection