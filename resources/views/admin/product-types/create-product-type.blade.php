@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Product Types', 'link'=> route('admin.product.types.manage')],
    ['name' => 'Create', 'link'=> '#']
]" />

<div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 max-w-2xl mx-auto">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Create New Product Type
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                Add a new product type with name, code and status
            </p>
        </div>
        <a href="{{ route('admin.product.types.manage') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">View Product Types</a>
    </div>

    <!-- Form -->
    <form action="" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Type Name</label>
            <input type="text"
                   placeholder="Enter type name"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
        </div>

        <!-- Code -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Code</label>
            <input type="text"
                   placeholder="Enter code"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Status</label>
            <select class="w-full px-4 py-2 border rounded-lg dark:bg-white/10 dark:text-white">
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between pt-4">

            <a href="{{ route('admin.product.types.manage') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg text-sm">
                Cancel
            </a>

            <button type="button"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Save
            </button>

        </div>

    </form>

</div>

@endsection