@extends('layouts.app')

@section('content')

    <x-common.page-breadcrumb
        :pageTitle="[
        ['name' => 'Collections', 'link'=> '/collections'],
        ['name' => 'Payment History', 'link' => '#']
    ]"
    />

    <!-- Customer Header -->
    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    {{ $customer->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg px-4 py-3 shadow-sm min-w-[140px]">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Paid</p>
                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                        {{ number_format((float) $totalCredit, 2) }}
                    </p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3 shadow-sm min-w-[140px]">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Owed</p>
                    <p class="text-lg font-semibold text-red-600 dark:text-red-400">
                        {{ number_format((float) $totalDebit, 2) }}
                    </p>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg px-4 py-3 shadow-sm min-w-[140px]">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Current Balance</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ number_format((float) $customer->current_balance, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Timeline -->
    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6">

        <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-4">
            Transaction History
        </h3>

        @if($transactions->isEmpty())
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <span class="iconify text-4xl mb-2" data-icon="lucide:receipt"></span>
                <p>No transactions recorded yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Reference</th>
                        <th class="px-4 py-3 text-left">Note</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($transactions as $tx)
                        @php
                            $isCredit = $tx->direction === 'credit';

                            $typeLabels = [
                                'sale_payment'    => 'Sale Payment',
                                'due_collection'  => 'Due Collection',
                                'refund'          => 'Refund',
                                'adjustment'      => 'Adjustment',
                                'opening_balance' => 'Opening Balance',
                            ];

                            $typeIcons = [
                                'sale_payment'    => 'lucide:shopping-cart',
                                'due_collection'  => 'lucide:hand-coins',
                                'refund'          => 'lucide:rotate-ccw',
                                'adjustment'      => 'lucide:settings-2',
                                'opening_balance' => 'lucide:flag',
                            ];
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors">

                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ $tx->created_at->format('d M Y') }}
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $tx->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $isCredit
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'
                                            : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' }}">
                                        <span class="iconify" data-icon="{{ $typeIcons[$tx->type] ?? 'lucide:circle-dollar-sign' }}" data-width="12"></span>
                                        {{ $typeLabels[$tx->type] ?? ucfirst(str_replace('_', ' ', $tx->type)) }}
                                    </span>
                            </td>

                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                @if($tx->transactionable)
                                    @if(method_exists($tx->transactionable, 'invoice_no') || isset($tx->transactionable->invoice_no))
                                        <span class="font-medium text-gray-800 dark:text-white">
                                                {{ $tx->transactionable->invoice_no }}
                                            </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">
                                                #{{ $tx->transactionable_id }}
                                            </span>
                                    @endif
                                @elseif($tx->reference_no)
                                    {{ $tx->reference_no }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 max-w-xs">
                                <p class="" title="{{ $tx->note }}">{{ $tx->note ?? '—' }}</p>
                                @if($tx->payment_method)
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                            via {{ ucfirst($tx->payment_method) }}
                                        </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right font-semibold whitespace-nowrap
                                    {{ $isCredit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $isCredit ? '+' : '−' }} {{ number_format((float) $tx->amount, 2) }}
                            </td>



                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
{{--                {{ $transactions->links() }}--}}
            </div>
        @endif

    </div>

@endsection
