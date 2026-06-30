@extends('layouts.app')
@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/units.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Units', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            Manage Units
        </h2>
        @can('measurement.view')

            <a href="{{ route('admin.unit.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                + Add Unit
            </a>
        @endcan
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="unitsTable"
               class="datatable w-full text-sm text-left border-separate border-spacing-y-2">

            <thead>
                <tr class="text-xs uppercase text-gray-500 dark:text-gray-300">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

@endsection
