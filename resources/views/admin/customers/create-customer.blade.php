@extends('layouts.app')

@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/customers.js'])
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Employees', 'link'=> route('admin.customers.manage')],
    ['name' => 'Create', 'link'=> '#']
]"/>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
            Create Employee
        </h2>

        @include('admin.customers._customer-form', [
            'action' => route('admin.employee.store'),
            'method' => 'POST',
            'employee' => null
        ])

    </div>

@endsection
