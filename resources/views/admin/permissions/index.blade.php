@extends('layouts.app')

@section('content')
{{-- <x-common.page-breadcrumb pageTitle="{{ $title }}" /> --}}
<x-common.page-breadcrumb :pageTitle="[['name' => 'Permissions', 'link'=> '#']]" />
<div class="bg-white dark:bg-white/[0.03] rounded-xl shadow border border-gray-200 dark:border-gray-800 p-4">
    
    <div class="flex mb-4">
        <a href="{{ route('admin.permissions.create') }}" class="px-3 py-1.5 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600 ml-auto">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              
            Add Permission
        </a>
    </div>
 
    <div class="overflow-x-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse ($permissions as $permission)
                <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-gray-800 rounded-xl p-4 shadow hover:shadow-md transition">
        
                    <!-- Title -->
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $permission->name }}
                        </h3>
                    </div>
        
                    <!-- Roles -->
                    <div class="mb-4">
                        @if ($permission->roles && count($permission->roles) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($permission->roles as $role)
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                No roles assigned
                            </span>
                        @endif
                    </div>
        
                    <!-- Actions -->
                    <div class="flex justify-end gap-3">
                        <button class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Edit
                        </button>
                        <button class="text-red-600 dark:text-red-400 hover:underline text-sm">
                            Delete
                        </button>
                    </div>
        
                </div>
            @empty
                <div class="col-span-12 text-center text-gray-500 dark:text-gray-400 py-6">
                    No permissions found.
                </div>
            @endforelse
        
        </div>
    </div>
</div>

@endsection