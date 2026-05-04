@extends('layouts.app')

@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/assets/js/suppliers.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Suppliers', 'link'=> route('admin.suppliers.manage')],
    ['name' => 'Create', 'link'=> '#']
]" />

{{-- <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow"> --}}
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
        Create Supplier
    </h2>

    @include('admin.suppliers._form', [
        'action' => route('admin.supplier.store'),
        'method' => 'POST',
        'supplier' => null
    ])

</div>

@endsection