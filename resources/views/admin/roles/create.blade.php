@extends('layouts.app')

@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('custom-scripts')
@vite(['resources/assets/js/create-role.js'])
@endsection

@section('content') 
<x-common.page-breadcrumb :pageTitle="[
            ['name'=>'Roles','link' => route('admin.roles') ], 
            ['name' => 'Create Role','link' =>'#']
        ]" />
<div class="bg-white dark:bg-white/[0.03] rounded-xl shadow border border-gray-200 dark:border-gray-800 p-4">
    
    <div class="flex mb-4 items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Role Information
        </h3>
        <a href="{{ route('admin.roles') }}" class="px-3 py-1.5 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600 ml-auto">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              
            View Roles
        </a>
    </div>
    
    <form action="{{ route('admin.roles.store') }}" method="POST" id="createRoleForm">
        @csrf
            
            <div class="w-[50%] mb-2">
                <label class="text-sm text-gray-600 dark:text-gray-300">Role Name</label>
                <input type="text"
                name="name"
                    class="w-full mt-1 px-3 py-2 rounded-lg border 
                           bg-gray-50 dark:bg-gray-700
                            outline-none focus:ring-2 focus:ring-blue-200
                           text-gray-800 dark:text-white">
            </div>
            <div>
                <button type="submit" class="px-3 py-1.5 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                      </svg>
                      Submit
                </button>
            </div>
        
    </form>
</div>

@endsection