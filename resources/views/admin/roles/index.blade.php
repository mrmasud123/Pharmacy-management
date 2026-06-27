@extends('layouts.app')

@section('custom-scripts')
    @vite(['resources/assets/js/roles.js'])
@endsection

@section('content')

<x-common.page-breadcrumb :pageTitle="[['name' => 'Roles', 'link'=> '#']]" />

<div class="bg-white dark:bg-white/3 rounded-xl shadow border p-4">


    <div class="flex mb-4">
        <a href="{{ route('admin.roles.create') }}"
           class="px-3 py-1.5 bg-blue-500 text-white rounded-lg ml-auto">
            Add Role?
        </a>
    </div>

    <div class="overflow-x-auto">

        <table id="rolesTable" class="datatable w-full text-left">
            <thead>
                <tr class="text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</div>

@endsection
