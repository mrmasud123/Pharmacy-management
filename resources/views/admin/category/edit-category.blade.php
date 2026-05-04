@extends('layouts.app')


@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/category.js'])
@endsection
@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Categories', 'link'=> route('admin.categories.manage')],
    ['name' => 'Edit', 'link'=> '#']
]" />

<div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Edit Category
            </h2>
            <p class="text-sm text-gray-500">
                Update the category details below.
            </p>
        </div>
        
        <a href="{{ route('admin.categories.manage') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">View Categories</a>
    </div>

    <form method="POST" 
      action="{{ route('admin.category.update', $category->id) }}"
      enctype="multipart/form-data"
      id="updateCategoryForm">
       
    @csrf
    @method('PUT')
        <div class="flex w-full gap-4">
            <!-- Name -->
            <div class="flex-1">
                <label class="text-sm text-gray-700 dark:text-gray-200">Category Name</label>
                <input type="text"
                name="category_name"
                value="{{ $category->name }}"
                class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
            </div>

            <!-- Code -->
            <div class="flex-1">
                <label class="text-sm text-gray-700 dark:text-gray-200">Category Code</label>
                <input type="text"
                    name="category_code"
                    value="{{ $category->code }}"
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
                @php
                    $imageUrl = $category->getFirstMediaUrl('categories') ?: 'https://placehold.co/300x250';
                @endphp

                <img id="imagePreview"
                    src="{{ $imageUrl }}"
                    class="w-75 h-62.5 object-cover rounded-lg border">

                
            </div>
            <!-- Status -->
            <div class="flex-1">
                
                <label class="text-sm text-gray-700 dark:text-gray-200">Status</label>
                <select name="category_status" class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
                    <option disabled>Choose category status</option>
                    <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
                Update
            </button>

        </div>

    </form>

</div>

@endsection