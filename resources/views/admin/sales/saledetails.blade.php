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
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- Invoice -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                        Invoice: <span class="text-green-600">{{ $sale->invoice_no }}</span>
                    </h2>
                </div>
            
                <!-- Customer Highlight Card -->
                <div class="bg-green-50 dark:bg-gray-800 border border-green-200 dark:border-gray-700 rounded-lg px-4 py-3 shadow-sm">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Customer
                    </p>
            
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ $sale->customer->name ?? 'N/A' }}
                    </p>
            
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $sale->customer->phone ?? 'N/A' }}
                    </p>
                </div>
            
            </div>
    
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
                    @php
                        $due = (float) $sale->due;
                    @endphp
                    <span class="font-semibold">
                        @if ($due >0)
                            Due : 
                        @elseif ($due < 0)
                            {{-- Due :  --}}
                            Over Paid : 
                        @else
                            Paid
                        @endif
                    </span>

                    <span class="
                        {{
                            $due > 0
                                ? 'text-red-500'
                                : ($due < 0
                                    ? 'text-yellow-500'
                                    : 'text-green-500')
                        }}
                    ">
                        {{ number_format($due, 2) }}
                    </span>
                </div>
    
                {{-- <div>
                    <span class="font-semibold">Status:</span>
                    {{ ucfirst($sale->status) }}
                </div> --}}
    
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