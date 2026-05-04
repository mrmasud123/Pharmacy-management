@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Products', 'link'=> route('admin.products.manage')],
    ['name' => $product->name, 'link'=> '#'],
    ['name' => 'Batches', 'link'=> '#']
]" />

<div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Batch List - {{ $product->name }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                All stock batches for this product
            </p>
        </div>

        <a href="{{ route('admin.product.stock.create', $product->id) }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            + Add Stock
        </a>
    </div>

    <!-- Product Info -->
    <div class="mb-6 p-4 rounded-lg bg-gray-100 dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-300">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <strong class="text-gray-800 dark:text-gray-200">Code:</strong>
                {{ $product->code }}
            </div>
            <div>
                <strong class="text-gray-800 dark:text-gray-200">Barcode:</strong>
                {{ $product->barcode }}
            </div>
            <div>
                <strong class="text-gray-800 dark:text-gray-200">Total Stock:</strong>
                {{ $product->batches->sum('quantity') }}
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">

            <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm">
                <tr>
                    <th class="px-4 py-3 text-left">Batch No</th>
                    <th class="px-4 py-3 text-left">Supplier</th>
                    <th class="px-4 py-3 text-left">Unit</th>
                    <th class="px-4 py-3 text-left">Expire Date</th>
                    <th class="px-4 py-3 text-left">Purchase Price</th>
                    <th class="px-4 py-3 text-left">Sales Price</th>
                    <th class="px-4 py-3 text-left">Quantity</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm">

                @forelse($product->batches as $batch)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                        <td class="px-4 py-3">
                            {{ $batch->batch_no ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $batch->supplier->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $batch->unit->name ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $batch->expire_date }}
                        </td>

                        <td class="px-4 py-3">
                            {{ number_format($batch->purchase_price, 2) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ number_format($batch->sales_price, 2) }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="font-semibold">
                                {{ $batch->quantity }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-3">
                            @if($batch->quantity <= 0)
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300">
                                    Out of Stock
                                </span>
                            @elseif(\Carbon\Carbon::parse($batch->expire_date)->isPast())
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                                    Expired
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300">
                                    Available
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No batches found
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection