@extends('layouts.app')


@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/assets/js/create-sale.js')
@endsection
@section('content')

<div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">New Sale</h2>

        <div class="flex gap-2">
            <a href="{{ route('admin.sales.index') }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm">Manage Sale</a>
            <a href="#" class="bg-green-700 text-white px-3 py-1 rounded text-sm">POS Sale</a>
        </div>
    </div>

    <!-- Customer + Date -->
    <div class="grid grid-cols-2 gap-4 mb-6">

        <!-- Customer -->
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Customer Name/Phone *
            </label>

            <div class="flex gap-2">
                    <select id="customerSelect" name="customer_id" placeholder="Search customer..." class="w-100" ></select>

                    <button type="button" id="addCustomerBtn" class="bg-green-600 text-white px-3 rounded">+</button>
            </div>
        </div>

        <div class=" ">
            {{-- <div class="flex-1"> --}}
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                    Sale date *
                </label>

                <x-form.date-picker
                    id="date_pick"
                    name="sale_date"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100"
                       required
                    placeholder="Date Picker"
                    defaultDate="{{ now()->format('Y-m-d') }}"
                />
            </div>
    </div>
    <form action="" id="saleForm">
        <!-- POS Table -->
        <div class="overflow-visible border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="mb-3 p-3">
                <button type="button" id="addRow"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    + Add Product
                </button>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="p-2">Item Information *</th>
                        <th class="p-2">Desc</th>
                        <th class="p-2">Batch No *</th>
                        <th class="p-2">Av. Qty</th>
                        <th class="p-2">Unit</th>
                        <th class="p-2">Qty *</th>
                        <th class="p-2">Rate *</th>
                        <th class="p-2">Discount %</th>
                        <th class="p-2">VAT %</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>

                <tbody id="sale_items">

                </tbody>

            </table>

        </div>

        <div class="mt-4 flex justify-end">
            <div class="w-full max-w-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">

                <div class="flex justify-between items-center mb-2">
                    <span class="text-md text-gray-600 dark:text-gray-300">Grand Total</span>
                    <input type="number" id="grand_total" readonly
                        class="w-32 text-right font-semibold text-lg bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white">
                </div>

            </div>
        </div>
        <!-- Sale Details -->
        <div class="mt-6">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Sale Details
            </label>

            <textarea rows="3" placeholder="Write sale note..."
                class="w-full input-style"></textarea>
        </div>

        <!-- Payment -->
        <div class="grid grid-cols-2 gap-4 mt-6">

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Payment Type
                </label>

                <select class="w-full input-style">
                    <option selected>Cash</option>
                    <option>Bkash</option>
                    <option>Nagad</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Paid Amount
                </label>

                <input name="paid" type="number" step="any" placeholder="0.00"
                    class="w-full input-style" />
            </div>

        </div>

        <!-- Button -->
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                Save Sale
            </button>
        </div>

    </form>

</div>

@endsection
