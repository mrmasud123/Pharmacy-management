<form action="{{ $action }}" method="POST" id="employeeForm">
    @csrf

    @if(isset($method) && $method !== 'POST')
        @method($method)
    @endif

    <div class="flex flex-col gap-5">
 
        <!-- Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Employee Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Employee Name <span class="text-red-500">*</span> 
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $employee->name ?? '') }}"
                       placeholder="Enter employee name"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              placeholder-gray-400 dark:placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Employee Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Employee Code
                </label>

                <input type="text"
                       name="employee_code"
                       value="{{ old('employee_code', $employee->employee_code ?? '') }}"
                       placeholder="EMP-001"
                       readonly 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              placeholder-gray-400 dark:placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Designation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Designation
                </label>

                <input type="text"
                       name="designation"
                       value="{{ old('designation', $employee->designation ?? '') }}"
                       placeholder="Software Engineer"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              placeholder-gray-400 dark:placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email', $employee->email ?? '') }}"
                       placeholder="Enter email"
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
                       value="{{ old('phone', $employee->phone ?? '') }}"
                       placeholder="Enter phone number"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              placeholder-gray-400 dark:placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Gender
                </label>

                <select name="gender"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                               bg-white dark:bg-gray-800
                               text-gray-800 dark:text-gray-100
                               focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">Select Gender</option>

                    <option value="Male"
                        {{ old('gender', $employee->gender ?? '') == 'Male' ? 'selected' : '' }}>
                        Male
                    </option>

                    <option value="Female"
                        {{ old('gender', $employee->gender ?? '') == 'Female' ? 'selected' : '' }}>
                        Female
                    </option>

                    <option value="Other"
                        {{ old('gender', $employee->gender ?? '') == 'Other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>
            </div>

        </div>

        <!-- Row 3 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Join Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Join Date
                </label>

                {{-- <input type="date"
                       name="join_date"
                       value="{{ old('join_date', isset($employee->join_date) ? date('Y-m-d', strtotime($employee->join_date)) : '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              focus:outline-none focus:ring-2 focus:ring-blue-500"> --}}
                <x-form.date-picker 
                    id="join_date" 
                    name="join_date"
                    value="{{ old('join_date', isset($employee->join_date) ? date('Y-m-d', strtotime($employee->join_date)) : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              bg-white dark:bg-gray-800 
                              text-gray-800 dark:text-gray-100"
                       required
                    placeholder="Pick join date" 
                    
                />
            </div>

            <!-- Date of Birth -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Date of Birth
                </label>
                              
                <x-form.date-picker 
                    id="dob" 
                    name="dob"
                    value="{{ old('dob', isset($employee->dob) ? date('Y-m-d', strtotime($employee->dob)) : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                        bg-white dark:bg-gray-800 
                                        text-gray-800 dark:text-gray-100"
                    required
                    placeholder="Pick birth date" 
                              
                />
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

                    <option value="1"
                        {{ old('is_active', $employee->is_active ?? 1) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('is_active', $employee->is_active ?? 1) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <!-- Row 4 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Basic Salary -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Basic Salary
                </label>

                <input type="number"
                        min="0"
                       step="0.01"
                       name="basic_salary"
                       value="{{ old('basic_salary', $employee->basic_salary ?? 0) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Allowance -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Allowance
                </label>

                <input type="number"
                        min="0"
                       step="0.01"
                       name="allowance"
                       value="{{ old('allowance', $employee->allowance ?? 0) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Deduction -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Deduction
                </label>

                <input type="number"
                        min="0"
                       step="0.01"
                       name="deduction"
                       value="{{ old('deduction', $employee->deduction ?? 0) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-gray-100
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

        <!-- Row 5 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            
            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Password
                    @if(!isset($employee))
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                              
                              <div x-data="{ showPassword: false }" class="relative">
                                <input :type="showPassword ? 'text' : 'password'" placeholder="Enter your password"
                                name="password"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                <span @click="showPassword = !showPassword"
                                    class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer">
                                    <svg x-show="!showPassword" class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" />
                                    </svg>
                    
                                    <svg x-show="showPassword" class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" />
                                    </svg>
                                </span>
                            </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Address
                </label>

                <textarea name="address"
                          rows="2"
                          placeholder="Enter employee address"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                 bg-white dark:bg-gray-800
                                 text-gray-800 dark:text-gray-100
                                 placeholder-gray-400 dark:placeholder-gray-500
                                 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $employee->address ?? '') }}</textarea>
            </div>

        </div>
        
        <div class="w-[50%]">
            
            <x-form.form-elements.dropzone />
        </div>

    </div>

    <!-- Buttons -->
    <div class="flex justify-end mt-6 gap-3">

        <a href="{{ route('admin.employees.manage') }}"
           class="px-4 py-2 bg-gray-200 dark:bg-gray-700
                  text-gray-700 dark:text-gray-200
                  rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
            Cancel
        </a>

        <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg
                       hover:bg-blue-700 shadow">
            {{ isset($employee) ? 'Update Employee' : 'Save Employee' }}
        </button>

    </div>

</form>