@extends('layouts.app')

@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/assets/js/product-types.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Product Types', 'link'=> route('admin.product.type.manage')],
    ['name' => isset($productType) ? 'Edit Product Type' : 'Create Product Type', 'link'=> '#']
]" />

<div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                {{ isset($productType) ? 'Edit Product Type' : 'Create New Product Type' }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                {{ isset($productType) ? 'Update product type details' : 'Add a new product type with code and status' }}
            </p>
        </div>
        <a href="{{ route('admin.product.type.manage') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
            View Product Types
        </a>
    </div>

    <!-- Form -->
    <form id="productTypeForm"
          action="{{ isset($productType) ? route('admin.product.type.update', $productType->id) : route('admin.product.type.store') }}"
          method="POST"
          class="space-y-5">
        @csrf

        @if(isset($productType))
            @method('PUT')
        @endif

        <!-- Product Type Name + Code + Status -->
        <div class="w-full flex gap-3">

            <!-- Product Type Name -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Product Type Name
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $productType->name ?? '') }}"
                       placeholder="Enter product type name"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white/10 dark:text-white"
                       required>
            </div>

            <!-- Product Type Code -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Product Type Code
                </label>
                <input type="text"
                       name="code"
                       value="{{ old('code', $productType->code ?? '') }}"
                       placeholder="Enter product type code (e.g. APL)"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white/10 dark:text-white"
                       required>
            </div>

            <!-- Status -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Status
                </label>

                <select name="status"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white/10 dark:text-white"
                        required>
                        <option value="null" disabled selected>Select status</option>
                    <option value="1" {{ old('status', $productType->status ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0" {{ old('status', $productType->status ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between pt-4">

            <a href="{{ route('admin.product.type.manage') }}"
               class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition">
                Cancel
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                {{ isset($productType) ? 'Update Product Type' : 'Save Product Type' }}
            </button>

        </div>

    </form>

</div>

@endsection