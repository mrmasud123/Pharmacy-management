@extends('layouts.app')

@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/suppliers.js'])
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Suppliers', 'link' => '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Manage Suppliers
            </h2>

            <a href="{{ route('admin.supplier.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                + Add Supplier
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="suppliersTable" class="datatable w-full text-sm text-left border-separate border-spacing-y-2">

                <thead>
                    <tr class="text-xs uppercase text-gray-500 dark:text-gray-300">

                        <th class="px-4 py-3 text-black dark:text-white">Name</th>
                        <th class="px-4 py-3 text-black dark:text-white">Company</th>
                        <th class="px-4 py-3 text-black dark:text-white">Email</th>
                        <th class="px-4 py-3 text-black dark:text-white">Phone</th>
                        <th class="px-4 py-3 text-black dark:text-white">Address</th>
                        <th class="px-4 py-3 text-black dark:text-white">Opening Due</th>
                        <th class="px-4 py-3 text-black dark:text-white">Status</th>
                        <th class="px-4 py-3 text-black dark:text-white text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

@endsection