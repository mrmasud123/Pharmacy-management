@extends('layouts.app')

@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/category.js'])
@endsection
@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Categories', 'link' => '#']]" />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Manage Categories
            </h2>

            <a href="{{ route('admin.categories.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                + Add Category
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="categoryTable" class="datatable w-full text-sm text-left border-separate border-spacing-y-2">

                <thead>
                    <tr>
                        {{-- <th>Sl.</th> --}}
                        <th class="text-black dark:text-white">Category Name</th>
                        {{-- <th>Category Code</th> --}}
                        <th class="text-black dark:text-white">Status</th>
                        <th class="text-black dark:text-white">Image</th>
                        <th class="text-black dark:text-white">Action</th>
                    </tr>
                </thead>

                {{-- <tbody>
                    @forelse($categories as $key => $category)
                    <tr class="bg-gray-50 dark:bg-white/5 rounded-lg shadow-sm hover:shadow-md transition">

                        <td class="px-4 py-3 dark:text-white">
                            {{ $key + 1 }}
                        </td>

                        <td class="px-4 py-3 font-semibold text-gray-800 dark:text-white">
                            {{ $category->name }}
                        </td>

                        <td class="px-4 py-3 dark:text-white">
                            {{ $category->code }}
                        </td>

                        <td class="px-4 py-3">
                            @if($category->status == 'active')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-lg">
                                Active
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-lg">
                                Inactive
                            </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">

                                <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}"
                                    class="px-2 py-1 text-xs bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200">
                                    Edit
                                </a>

                                <a href="{{ route('admin.categories.destroy', ['id' => $category->id]) }}"
                                    onclick="return confirm('Are you sure?')"
                                    class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">
                                    Delete
                                </a>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            No categories found
                        </td>
                    </tr>
                    @endforelse
                </tbody> --}}

            </table>
        </div>

    </div>

@endsection