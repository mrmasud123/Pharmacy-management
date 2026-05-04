@extends('layouts.app')


@section('vendor-scripts')
    @vite(['resources/assets/js/products.js'])
@endsection
@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Products', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

    <div class="flex justify-between mb-5">
        <h2 class="text-xl font-semibold dark:text-white">Manage Products</h2>

        <a href="{{ route('admin.product.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            + Add Product 
        </a>
        {{-- <span class="iconify text-xl text-gray-600" data-icon="lucide:home"></span> --}}
       
    </div>

    <div class="overflow-x-auto">
        <table id="productTable" class="datatable w-full text-sm border-separate border-spacing-y-2">

            <thead>
                <tr class="text-xs text-gray-500 uppercase">
                    <th>Name</th>
                    {{-- <th>Supplier</th> --}}
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Product Type</th>
                    
                    <th>Action</th>
                </tr>
            </thead>

        </table>
    </div>

</div>

@endsection