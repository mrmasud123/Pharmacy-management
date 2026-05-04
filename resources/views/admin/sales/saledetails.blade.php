@extends('layouts.app')

@section('vendor-scripts')
    @vite(['resources/assets/js/sales.js'])
@endsection

@section('content')

<x-common.page-breadcrumb 
    :pageTitle="[
        ['name' => 'Sales', 'link'=> '/sales'],
        ['name' => 'Sale ' . $sale->invoice_no, 'link' => '#']
    ]" 
/>

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Invoice: {{ $sale->invoice_no }}
            </h2>
    
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-sm text-gray-600 dark:text-gray-300">
    
                <div>
                    <span class="font-semibold">Total:</span>
                    {{ number_format($sale->total, 2) }}
                </div>
    
                <div>
                    <span class="font-semibold">Discount:</span>
                    {{ number_format($sale->discount, 2) }}
                </div>
    
                <div>
                    <span class="font-semibold">Tax:</span>
                    {{ number_format($sale->tax, 2) }}
                </div>
    
                <div>
                    <span class="font-semibold">Grand Total:</span>
                    {{ number_format($sale->grand_total, 2) }}
                </div>
    
                <div>
                    <span class="font-semibold">Paid:</span>
                    {{ number_format($sale->paid, 2) }}
                </div>
    
                <div>
                    <span class="font-semibold">Due:</span>
                    <span class="{{ $sale->due > 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ number_format($sale->due, 2) }}
                    </span>
                </div>
    
                <div>
                    <span class="font-semibold">Status:</span>
                    {{ ucfirst($sale->status) }}
                </div>
    
                <div>
                    <span class="font-semibold">Date:</span>
                    {{ $sale->created_at->format('d M Y H:i') }}
                </div>
    
            </div>
        </div>
    
        <!-- Items Table -->
        <div class="overflow-x-auto">
    
            <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
    
                <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Batch No</th>
                        <th class="px-4 py-3 text-left">Qty</th>
                        <th class="px-4 py-3 text-left">Unit Price</th>
                        <th class="px-4 py-3 text-left">Discount</th>
                        <th class="px-4 py-3 text-left">Tax</th>
                        <th class="px-4 py-3 text-left">Total</th>
                    </tr>
                </thead>
    
                <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm">
    
                    @forelse($sale->items as $item)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
    
                            <td class="px-4 py-3">
                                {{ $item->product->name ?? 'N/A' }}
                            </td>
    
                            <td class="px-4 py-3">
                                {{ $item->productBatch->batch_no ?? 'N/A' }}
                            </td>
    
                            <td class="px-4 py-3">
                                {{ $item->quantity }}
                            </td>
    
                            <td class="px-4 py-3">
                                {{ number_format($item->unit_price, 2) }}
                            </td>
    
                            <td class="px-4 py-3 text-red-500">
                                {{ number_format($item->discount, 2) }}
                            </td>
    
                            <td class="px-4 py-3 text-blue-500">
                                {{ number_format($item->tax, 2) }}
                            </td>
    
                            <td class="px-4 py-3 font-semibold">
                                {{ number_format($item->total, 2) }}
                            </td>
    
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">
                                No items found
                            </td>
                        </tr>
                    @endforelse
    
                </tbody>
    
            </table>
        </div>
    
    </div>

@endsection