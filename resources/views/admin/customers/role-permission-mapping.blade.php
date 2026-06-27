@extends('layouts.app')

@section('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/customers.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'User', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/3 rounded-xl shadow p-4">

    <div class="flex mb-4">
        <a href="{{ route('role.permission.mapping') }}"
           class="px-3 py-1.5 bg-blue-500 text-white rounded-lg ml-auto">
            View Mapping
        </a>
    </div>

    <form id="employeeRoleMappingForm" action="{{ route('role.permission.mapping.store') }}" method="POST">
        @csrf
        <input type="text" hidden name="user_id" value="{{ $user->id }}">
        {{-- Employee Info Card --}}
        <div
            class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-linear-to-r from-blue-50 to-indigo-50 shadow-sm dark:border-gray-700 dark:from-gray-900 dark:to-gray-800">

            <div class="flex flex-col gap-6 p-6 md:flex-row md:items-center md:justify-between">

                {{-- Left Side --}}
                <div class="flex items-center gap-4">

                    {{-- Avatar --}}
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-2xl font-bold text-white shadow">

                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>

                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                            {{ $user->name }}
                        </h2>

                        <div
                            class="mt-1 flex flex-col gap-1 text-sm text-gray-600 dark:text-gray-300 md:flex-row md:items-center md:gap-4">
                            <span>
                                <strong>Email:</strong> {{ $user->email ?? 'N/A' }}
                            </span>

                        </div>

                    </div>

                </div>

                {{-- Assigned Roles --}}
                <div class="min-w-62.5">

                    <h3 class="mb-2 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Assigned Roles
                    </h3>

                    <div class="flex flex-wrap gap-2">

                        @forelse($user->roles as $role)

                            <span
                                class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">

                                {{ $role->name }}
                            </span>

                        @empty

                            <span
                                class="rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-600 dark:bg-red-900/30 dark:text-red-300">

                                No roles assigned
                            </span>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>
        {{-- Roles Grid --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

    @foreach($roles as $role)

        @php
            $isAssigned = $user->roles->contains('id', $role->id);
        @endphp

        <label class="block cursor-pointer">

            {{-- Checkbox --}}
            <input
                type="checkbox"
                name="roles[]"
                value="{{ $role->id }}"
                class="peer hidden"
                {{ $isAssigned ? 'checked' : '' }}>

            {{-- Card --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:border-blue-400 hover:shadow-md

                peer-checked:border-blue-500
                peer-checked:bg-blue-50

                dark:border-gray-700
                dark:bg-gray-900
                dark:hover:border-blue-500
                dark:peer-checked:border-blue-500
                dark:peer-checked:bg-blue-900/20">

                <div class="flex items-center justify-between">

                    {{-- Left Side --}}
                    <div class="flex items-center gap-4">

                        {{-- Icon --}}
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-600 transition-all

                            peer-checked:bg-blue-600
                            peer-checked:text-white

                            dark:bg-gray-800
                            dark:text-gray-300">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 14l6.16-3.422A12.083 12.083 0 0120 17.944L12 21l-8-3.056a12.083 12.083 0 011.84-7.366L12 14z" />
                            </svg>

                        </div>

                        {{-- Text --}}
                        <div>

                            <h4 class="text-base font-semibold text-gray-800 dark:text-white">
                                {{ $role->name }}
                            </h4>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Role access permission
                            </p>

                        </div>

                    </div>

                    {{-- Check Circle --}}
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-gray-300 transition-all

                        peer-checked:border-blue-600
                        peer-checked:bg-blue-600

                        dark:border-gray-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-white opacity-0 transition-opacity peer-checked:opacity-100"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="3"
                                d="M5 13l4 4L19 7" />
                        </svg>

                    </div>

                </div>

            </div>

        </label>

    @endforeach

</div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <button type="submit"
                class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">
                Save Mapping
            </button>
        </div>

    </form>

</div>

@endsection
