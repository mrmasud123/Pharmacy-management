@extends('layouts.app')

@section('vendor-scripts')

    @vite(['resources/assets/js/sales.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Sales', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            Sales List
        </h2>

        {{-- <a href="{{ route('admin.sales.create') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
            + Add Sale
        </a> --}}
    </div>
    
    <div class="flex flex-wrap items-end gap-3 mb-6">

        <!-- Customer -->
        <div>
            <label class="text-xs text-gray-500 dark:text-gray-300">Customer</label>
            <select id="filterCustomer" class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-white/5 dark:text-white">
                <option value="">All</option>
                {{-- @foreach($sales->pluck('customer.name')->filter()->unique() as $customer)
                    <option value="{{ $customer }}">{{ $customer }}</option>
                @endforeach --}}
            </select>
        </div>
    
        <!-- Invoice -->
        <div>
            <label class="text-xs text-gray-500 dark:text-gray-300">Invoice</label>
            <input type="text" id="filterInvoice"
                   class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-white/5 dark:text-white"
                   placeholder="Search invoice">
        </div>
    
        <!-- Start Date -->
        <div>
            <label class="text-xs text-gray-500 dark:text-gray-300">Start Date</label>
            <input type="date" id="startDate"
                   class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-white/5 dark:text-white">
        </div>
    
        <!-- End Date -->
        <div>
            <label class="text-xs text-gray-500 dark:text-gray-300">End Date</label>
            <input type="date" id="endDate"
                   class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-white/5 dark:text-white">
        </div>
    
        <!-- Button -->
        <div class="flex items-end">
            <button id="filterBtn"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                Apply
            </button>
        </div>
    
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="salesTable" class="w-full text-sm text-left border-separate border-spacing-y-2">

            <thead>
                <tr class="text-xs uppercase text-gray-500 dark:text-gray-300">
                  
                    <th class="px-4 py-3">Invoice</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Paid</th>
                    <th class="px-4 py-3">Due</th>
                    {{-- <th class="px-4 py-3">Status</th> --}}
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>

            

        </table>
    </div>

</div>

@endsection