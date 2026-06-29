@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="[['name' => 'Online Users', 'link'=> '#']]" />

    <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-lg border border-gray-200 dark:border-gray-800 p-6 transition-colors">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Currently Logged In Users
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Staff active within the last 5 minutes
                </p>
            </div>

            <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                </span>
                <span class="text-sm font-medium text-green-700 dark:text-green-400">
                    {{ $onlineUsers->count() }} {{ Str::plural('user', $onlineUsers->count()) }} online
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 dark:bg-gray-800/60 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3.5 text-left">User</th>
                    <th class="px-5 py-3.5 text-left">Email</th>
                    <th class="px-5 py-3.5 text-left">Status</th>
                    <th class="px-5 py-3.5 text-left">Last Active</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($onlineUsers as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">

                        <!-- Avatar + Name -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-brand-100 dark:bg-brand-500/15 text-brand-600 dark:text-brand-400 font-semibold text-sm uppercase">
                                        {{ Str::substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-green-500 border-2 border-white dark:border-gray-900"></span>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">
                                    {{ $user->name }}
                                </span>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </td>

                        <!-- Status badge -->
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Online
                            </span>
                        </td>

                        <!-- Last active -->
                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap"
                            title="{{ $user->last_activity_at?->format('M d, Y h:i A') ?? '' }}">
                            {{ $user->last_activity_at?->diffForHumans() ?? 'N/A' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" class="opacity-50">
                                    <path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="text-sm">No active users right now</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
@endsection
