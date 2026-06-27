@extends('layouts.app')
@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/due-collection.js'])
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Collection', 'link'=> '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Due Collection List
            </h2>
        </div>

        <div class="flex flex-wrap items-end gap-3 mb-6">

            <!-- Customer -->
            <div class="flex-1">
                <label class="text-xs text-gray-500 dark:text-gray-300">Customer</label>

                <select id="customerSelect" name="customer_id" placeholder="Search customer..." class="mt-1" ></select>
            </div>

            <div class="flex-1">
                <label class="text-xs text-gray-500 dark:text-gray-300">Sale Date</label>
                <x-form.date-picker
                    id="collection_date"
                    name="collection_date"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100"
                    required
                    placeholder="Date Picker"

                />
            </div>

            <div class="flex items-end ms-auto">
                <button id="clearFilter"
                        class="ms-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg shadow transition">
                    Reset
                </button>
            </div>

        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="dueCollectionTable" class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total Invoice</th>
                    <th class="px-4 py-3" align="right">Total</th>
                    <th class="px-4 py-3">Discount</th>
                    <th class="px-4 py-3">Tax</th>
                    <th class="px-4 py-3">Grand Total</th>
                    <th class="px-4 py-3">Paid</th>
                    <th class="px-4 py-3">Due</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>


@endsection
