@extends('layouts.app')

@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/assets/js/products.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Products', 'link'=> route('admin.products.manage')],
    ['name' => isset($product) ? 'Edit Product' : 'Create Product', 'link'=> '#']
]" />

<div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                {{ isset($product) ? 'Edit Product' : 'Create New Product' }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                {{ isset($product) ? 'Update product details' : 'Fill in details to create a product' }}
            </p>
        </div>

        <a href="{{ route('admin.products.manage') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow">
            View Products
        </a>
    </div>

    <!-- Form -->
    <form action="{{ isset($product) ? route('admin.product.update', $product->id) : route('admin.product.store') }}"
          method="POST"
          id="productForm"
          enctype="multipart/form-data"
          class="space-y-5">

        @csrf
        @if(isset($product)) @method('PUT') @endif
 
        <div class="flex gap-4">
 
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Product Name</label>
                <input type="text" name="name" placeholder="Enter product name"
                       value="{{ old('name', $product->name ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 
                       placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Product Code</label>
                <input type="text" name="code" placeholder="Enter unique code"
                       value="{{ old('code', $product->code ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 
                       placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Barcode</label>
                <input type="text" name="barcode" placeholder="Optional barcode"
                       value="{{ old('barcode', $product->barcode ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 
                       placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

        <div class="flex gap-4">

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Supplier</label>
                <select name="supplier_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                        bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Brand</label>
                <select name="brand_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                        bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Type</label>
                <select name="product_type_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                        bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}"
                            {{ old('product_type_id', $product->product_type_id ?? '') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="flex gap-4">

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                        bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Expire Date</label>
                <input type="date" name="expire_date"
                       value="{{ old('expire_date', $product->expire_date ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div> --}}

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Alert Quantity</label>
                <input type="number" name="alert_quantity" placeholder="Default 10"
                       value="{{ old('alert_quantity', $product->alert_quantity ?? 10) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 
                       placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Product Image</label>
            <input type="file" name="image"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                   bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            <small class="text-gray-400">Recommended size: 300 x 250 px</small>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.products.manage') }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-white rounded-lg">
                Cancel
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                {{ isset($product) ? 'Update Product' : 'Save Product' }}
            </button>
        </div>

    </form>

</div>

@endsection