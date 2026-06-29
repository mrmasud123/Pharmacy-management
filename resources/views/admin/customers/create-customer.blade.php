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

        <form action="{{route('admin.customers.store')}}" method="POST" id="customerForm">
            @csrf

            @if(isset($method) && $method !== 'POST')
                @method($method)
            @endif

            <div class="flex flex-col gap-5">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Customer Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Customer Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $customer->name ?? '') }}"
                               placeholder="Enter customer name"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              placeholder-gray-400 dark:placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $customer->phone ?? '') }}"
                               placeholder="Enter phone number"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              placeholder-gray-400 dark:placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Status
                        </label>
                        <select name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                               bg-white dark:bg-gray-800
                               text-gray-800 dark:text-gray-100
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1" {{ old('is_active', $customer->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $customer->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 gap-4">

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Address
                        </label>
                        <textarea name="address"
                                  rows="3"
                                  placeholder="Enter customer address"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                 bg-white dark:bg-gray-800
                                 text-gray-800 dark:text-gray-100
                                 placeholder-gray-400 dark:placeholder-gray-500
                                 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $customer->address ?? '') }}</textarea>
                    </div>

                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end mt-6 gap-3">
                <a href="{{ route('admin.customers.manage') }}"
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700
                  text-gray-700 dark:text-gray-200
                  rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg
                       hover:bg-blue-700 shadow">
                    {{ isset($customer) ? 'Update Customer' : 'Save Customer' }}
                </button>
            </div>

        </form>

    </div>

@endsection
