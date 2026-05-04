@extends('layouts.app')

@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/assets/js/units.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[
    ['name' => 'Units', 'link'=> route('admin.units.manage')],
    ['name' => isset($unit) ? 'Edit Unit' : 'Create Unit', 'link'=> '#']
]" />

<div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                {{ isset($unit) ? 'Edit Unit' : 'Create New Unit' }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                {{ isset($unit) ? 'Update unit details' : 'Add a new unit with code and status' }}
            </p>
        </div>
        <a href="{{ route('admin.units.manage') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
            View Units
        </a>
    </div>

    <!-- Form -->
    <form id="unitForm"
          action="{{ isset($unit) ? route('admin.unit.update', $unit->id) : route('admin.unit.store') }}"
          method="POST"
          class="space-y-5">
        @csrf

        @if(isset($unit))
            @method('PUT')
        @endif

        <!-- Unit Name + Code + Status -->
        <div class="w-full flex gap-3">

            <!-- Unit Name -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Unit Name
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $unit->name ?? '') }}"
                       placeholder="Enter unit name"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white/10 dark:text-white"
                       required>
            </div>

            

            <!-- Status -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Status
                </label>

                <select name="is_active"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white/10 dark:text-white"
                        required>
                    <option value="1" {{ old('is_active', $unit->is_active ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0" {{ old('is_active', $unit->is_active ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between pt-4">

            <a href="{{ route('admin.units.manage') }}"
               class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition">
                Cancel
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                {{ isset($unit) ? 'Update Unit' : 'Save Unit' }}
            </button>

        </div>

    </form>

</div>

@endsection