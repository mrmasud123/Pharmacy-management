<form action="{{ $action }}" method="POST" id="supplierForm">
    @csrf

    @if(isset($method) && $method !== 'POST')
        @method($method)
    @endif

    <div class="flex flex-col gap-4">

        <div class="w-full flex gap-3">
            <!-- Name -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                    value="{{ old('name', $supplier->name ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100 
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter supplier name" required>
            </div>

            <!-- Company -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Company Name
                </label>
                <input type="text" name="company_name"
                    value="{{ old('company_name', $supplier->company_name ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100 
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter company name">
            </div>

            <!-- Email -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Email
                </label>
                <input type="email" name="email"
                    value="{{ old('email', $supplier->email ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100 
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter email">
            </div>
        </div>

        <div class="w-full flex gap-3">
            <!-- Phone -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Phone <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone"
                    value="{{ old('phone', $supplier->phone ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100 
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter phone number" required>
            </div>

            <!-- Opening Balance -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Opening Balance (৳)
                </label>
                <input type="number" step="0.01" name="opening_balance"
                    value="{{ old('opening_balance', $supplier->opening_balance ?? 0) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Status -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Status
                </label>
                <select name="status"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="1" {{ old('status', $supplier->is_active ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ old('status', $supplier->is_active ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <!-- Address -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Address
                </label>
                <textarea name="address" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           bg-white dark:bg-gray-800 
                           text-gray-800 dark:text-gray-100 
                           placeholder-gray-400 dark:placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter address">{{ old('address', $supplier->address ?? '') }}</textarea>
            </div>
        </div>

    </div>

    <!-- Buttons -->
    <div class="flex justify-end mt-6 gap-3">
        <a href="{{ route('admin.suppliers.manage') }}"
           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 
                  text-gray-700 dark:text-gray-200 
                  rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
            Cancel
        </a>

        <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg 
                       hover:bg-blue-700 shadow">
            {{ isset($supplier) ? 'Update Supplier' : 'Save Supplier' }}
        </button>
    </div>
</form>