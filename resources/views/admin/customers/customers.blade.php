@extends('layouts.app')

@section('custom-scripts')
    @vite(['resources/assets/js/customers.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Customers', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/3 rounded-xl shadow p-4">

    <div class="flex mb-4">
        <a href="{{ route('admin.customers.create') }}"
           class="px-3 py-1.5 bg-blue-500 text-white rounded-lg ml-auto">
            Add Customer?
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="customerTable" class="w-full text-sm">
            <thead>
            <tr class="bg-gray-50 dark:bg-gray-800 text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <th class="px-4 py-3 text-left w-10">#</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Phone</th>
                <th class="px-4 py-3 text-left">Address</th>
                <th class="px-4 py-3 text-left w-32">Action</th>
            </tr>
            </thead>
        </table>
    </div>

</div>

@endsection
