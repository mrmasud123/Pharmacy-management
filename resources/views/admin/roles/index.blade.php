{{-- jquery datatable --}}
{{-- 
@extends('layouts.app')

@section('vendor-scripts')
    @vite(['resources/assets/js/datatables.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Roles', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/[0.03] rounded-xl shadow border border-gray-200 dark:border-gray-800 p-4">
    
    <div class="flex mb-4">
        <a href="{{ route('admin.roles.create') }}"
           class="px-3 py-1.5 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600 ml-auto">
            Add Role
        </a>
    </div>

    <div class="overflow-x-auto">
        <!-- ✅ IMPORTANT: ID added -->
        <table id="rolesTable" class="w-full text-left">
            <thead>
                <tr class="text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                    <th class="py-2">Role</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700 dark:text-gray-200">
                @forelse ($roles as $role)
                    <tr class="border-b h-[70px] dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        
                        <td class="py-2 font-medium">
                            {{ $role->name }}
                        </td>

                        <td>
                            @php $perms = $role->permissions; @endphp

                            @if($perms->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($perms->take(3) as $permission)
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach

                                    @if ($perms->count() > 3)
                                        <span class="text-xs text-gray-500">
                                            +{{ $perms->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">No permissions</span>
                            @endif
                        </td>

                        <td class="space-x-3">
                            <a href="{{ route('admin.add.permissions.to.role', $role->id) }}"
                               class="text-yellow-600 hover:underline">
                                Permissions
                            </a>

                            <button class="text-blue-600 hover:underline">
                                Edit
                            </button>

                            <button class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">
                            No roles found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection --}}

{{-- Yajra datatable --}}
@extends('layouts.app')

@section('vendor-scripts')
    @vite(['resources/assets/js/datatables.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Roles', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/[0.03] rounded-xl shadow border p-4">

    <div class="flex mb-4">
        <a href="{{ route('admin.roles.create') }}"
           class="px-3 py-1.5 bg-blue-500 text-white rounded-lg ml-auto">
            Add Role
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="rolesTable" class="w-full text-left">
            <thead>
                <tr class="text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

@endsection