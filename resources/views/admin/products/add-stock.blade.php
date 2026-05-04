@extends('layouts.app')


@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/assets/js/products.js'])
@endsection


@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Products', 'link'=> route('admin.products.manage')],
    ['name' => 'Add Stock', 'link'=> '#']
]" />

<div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Add Stock - {{ $product->name }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Create a new batch for this product
        </p>
    </div>

    <!-- Product Info Card -->
    <div class="mb-6 p-4 rounded-lg bg-gray-100 dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-300">
        <div class="grid grid-cols-3 gap-4">
            <div><strong class="text-gray-800 dark:text-gray-200">Code:</strong> {{ $product->code }}</div>
            <div><strong class="text-gray-800 dark:text-gray-200">Barcode:</strong> {{ $product->barcode }}</div>
            <div>
                <strong class="text-gray-800 dark:text-gray-200">Total Stock:</strong>
                {{-- {{ $product->batches->sum('quantity') }} --}}
            </div>
        </div>
    </div>

    <!-- Form -->
        <form id="batchForm"
            method="POST"
            action="{{ route('admin.product.stock.store', $product->id) }}"
            class="space-y-5">
        @csrf

        <!-- ROW 1 -->
        <div class="flex gap-4">

            <!-- Supplier -->
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Supplier
                </label>
                <select name="supplier_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               bg-white dark:bg-gray-800 
                               text-gray-800 dark:text-gray-100">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Batch No -->
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Batch No
                </label>
                <input type="text" name="batch_no"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Optional (auto-generated if empty)">
            </div>

        </div>

        <!-- ROW 2 -->
        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Expire Date *
                </label>
        
                <x-form.date-picker 
                    id="date_pick" 
                    name="expire_date"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100"
                       required
                    placeholder="Date Picker" 
                    defaultDate="{{ now()->format('Y-m-d') }}" 
                />
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Quantity *
                </label>
                <input type="number" name="quantity"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100"
                       required>
            </div>

        </div>

        <!-- ROW 3 -->
        <div class="flex gap-4">

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Product Unit *
                </label>
                <select name="unit_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               bg-white dark:bg-gray-800 
                               text-gray-800 dark:text-gray-100"
                        required>
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->name }}
                        </option>
                    @endforeach
            </select>
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Purchase Price *
                </label>
                <input type="number" step="0.01" name="purchase_price"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100"
                       required>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Sales Price *
                </label>
                <input type="number" step="0.01" name="sales_price"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100"
                       required>
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex justify-between pt-4">

            <a href="{{ route('admin.products.manage') }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 
                      text-gray-800 dark:text-gray-200 rounded-lg">
                Cancel
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 
                           text-white rounded-lg">
                Add Stock
            </button>

        </div>

    </form>

</div>

@endsection