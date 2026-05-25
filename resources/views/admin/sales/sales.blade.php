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
    </div>
    
    <div class="flex flex-wrap items-end gap-3 mb-6">

        <!-- Customer -->
        <div class="flex-1">
            <label class="text-xs text-gray-500 dark:text-gray-300">Customer</label>
        
            <select id="customerSelect" name="customer_id" placeholder="Search customer..." class="mt-1" ></select>
        </div>
        
        <!-- Invoice -->
        <div class="flex-1">
            <label class="text-xs text-gray-500 dark:text-gray-300">Invoice</label>
            <select id="invoiceSelect" name="invoice_id" placeholder="Search invoice..." class="mt-1" ></select> 
        </div>
     
        <div class="flex-1">
            <label class="text-xs text-gray-500 dark:text-gray-300">Sale Date</label> 
                   <x-form.date-picker 
                    id="sale_date" 
                    name="sale_date"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100"
                       required
                    placeholder="Date Picker" 
                    
                />
        </div>
     
        <div class="flex items-end ms-auto">
            {{-- <button id="filterBtn"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                Apply
            </button>  --}}
            <button id="clearFilter"
                    class="ms-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg shadow transition">
                Reset
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
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Paid</th>
                    <th class="px-4 py-3">Due</th> 
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>

            

        </table>
    </div>

</div>

@endsection