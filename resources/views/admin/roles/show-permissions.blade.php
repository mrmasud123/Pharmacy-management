@extends('layouts.app')

@section('content')
{{-- <x-common.page-breadcrumb pageTitle="{{ $title }}" /> --}}
<x-common.page-breadcrumb :pageTitle="[['name' => 'Roles', 'link'=> '#']]" />
<div class="bg-white dark:bg-white/[0.03] rounded-xl shadow border border-gray-200 dark:border-gray-800 p-4">
    
    <div class="flex mb-4 items-center justify-between">
        <h4 class="px-3 rounded-md text-xl bg-blue-200">Role : {{ $role->name }}</h4>
        <a href="{{ route('admin.roles') }}" class="px-3 py-1.5 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600  ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              
            View Roles
        </a>
    </div>
 
    <div class="overflow-x-auto">
        <form action="{{ route('admin.roles.update-permissions', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
        
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
                @foreach ($permissions as $permission)
                    <label 
                        class="flex items-center justify-between p-4 rounded-xl border 
                               border-gray-200 dark:border-gray-700
                               bg-gray-50 dark:bg-gray-800
                               hover:border-blue-400 hover:shadow-sm cursor-pointer transition">
        
                        <!-- Left: Name -->
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-800 dark:text-white">
                                {{ $permission->name }}
                            </span>
                        </div>
        
                        <!-- Right: Checkbox -->
                        <input type="checkbox"
                               name="permissions[]"
                               value="{{ $permission->id }}"
                               class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                               
                               {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                    </label>
                @endforeach
        
            </div>
        
            <!-- Submit -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Update Permissions
                </button>
            </div>
        </form>
    </div>
</div>

@endsection