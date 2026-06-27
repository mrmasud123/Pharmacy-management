@extends('layouts.app')

@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @vite(['resources/assets/js/invoice-payment.js'])
@endsection

@section('content')

    <x-common.page-breadcrumb
        :pageTitle="[
        ['name' => 'Collections', 'link'=> '/collections'],
        ['name' => 'Customer Sales', 'link' => '#']
    ]"
    />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 mb-6">

        <!-- Customer Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Customer: <span class="text-green-600 dark:text-green-400">{{ $customer->name }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone }}</p>
            </div>

            <div class="bg-red-50 dark:bg-red-50 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3 shadow-sm">
                <p class="text-xs text-gray-500 dark:text-gray-dark uppercase tracking-wide">Current Balance</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-dark">
                    {{ number_format((float) $customer->current_balance, 2) }}
                </p>
            </div>
        </div>

        <!-- Aggregate Summary -->
        @php
            $totalSum      = $customer->sales->sum(fn($s) => (float) $s->total);
            $discountSum   = $customer->sales->sum(fn($s) => (float) $s->discount);
            $taxSum        = $customer->sales->sum(fn($s) => (float) $s->tax);
            $grandTotalSum = $customer->sales->sum(fn($s) => (float) $s->grand_total);
            $paidSum       = $customer->sales->sum(fn($s) => (float) $s->paid);
            $dueSum        = $customer->sales->sum(fn($s) => (float) $s->due);
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-sm text-gray-600 dark:text-gray-300 border-t border-gray-200 dark:border-gray-700 pt-4">
            <div><span class="font-semibold text-gray-800 dark:text-white">Total:</span> {{ number_format($totalSum, 2) }}</div>
            <div><span class="font-semibold text-gray-800 dark:text-white">Discount:</span> {{ number_format($discountSum, 2) }}</div>
            <div><span class="font-semibold text-gray-800 dark:text-white">Tax:</span> {{ number_format($taxSum, 2) }}</div>
            <div><span class="font-semibold text-gray-800 dark:text-white">Grand Total:</span> {{ number_format($grandTotalSum, 2) }}</div>
            <div><span class="font-semibold text-gray-800 dark:text-white">Paid:</span> {{ number_format($paidSum, 2) }}</div>

            <div>
                @php $due = $dueSum; @endphp
                <span class="font-semibold text-gray-800 dark:text-white">
                    @if ($due > 0) Due :
                    @elseif ($due < 0) Over Paid :
                    @else Paid
                    @endif
                </span>
                <span class="{{ $due > 0 ? 'text-red-500 dark:text-red-400' : ($due < 0 ? 'text-yellow-500 dark:text-yellow-400' : 'text-green-500 dark:text-green-400') }}">
                    {{ number_format($due, 2) }}
                </span>
            </div>

            <div><span class="font-semibold text-gray-800 dark:text-white">Invoices:</span> {{ $customer->sales->count() }}</div>
        </div>
    </div>
    @forelse($customer->sales as $sale)
        @php
            $saleTotal      = (float) $sale->total;
            $saleDiscount   = (float) $sale->discount;
            $saleTax        = (float) $sale->tax;
            $saleGrandTotal = (float) $sale->grand_total;
            $salePaid       = (float) $sale->paid;
            $saleDue        = (float) $sale->due;
        @endphp

        <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 mb-4">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Invoice: <span class="text-green-600 dark:text-green-400">{{ $sale->invoice_no }}</span>
                    <span class="text-xs text-gray-400 dark:text-gray-500 ms-2">
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                    </span>
                </h3>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.invoice.edit', $sale->id) }}"
                       class="px-3 py-1 text-xs font-medium rounded bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-700 text-white transition-colors">
                        Edit
                    </a>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        {{ $saleDue > 0
                            ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'
                            : ($saleDue < 0
                                ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300'
                                : 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300') }}">
                        @if($saleDue > 0) Due: {{ number_format($saleDue, 2) }}
                        @elseif($saleDue < 0) Over Paid: {{ number_format(abs($saleDue), 2) }}
                        @else Paid
                        @endif
                    </span>

                    @if($saleDue > 0)
                        <button
                            type="button"
                            class="px-3 py-1 text-xs font-medium rounded bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white transition-colors"
                            data-pay-url="{{ route('admin.invoice.sales.pay-due', $sale->id) }}"
                            onclick="openPayDueModal(this, '{{ $sale->invoice_no }}', {{ $saleDue }})"
                        >
                            Pay Due
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm text-gray-600 dark:text-gray-300">
                <div><span class="font-semibold text-gray-800 dark:text-white">Total:</span> {{ number_format($saleTotal, 2) }}</div>
                <div><span class="font-semibold text-gray-800 dark:text-white">Discount:</span> {{ number_format($saleDiscount, 2) }}</div>
                <div><span class="font-semibold text-gray-800 dark:text-white">Tax:</span> {{ number_format($saleTax, 2) }}</div>
                <div><span class="font-semibold text-gray-800 dark:text-white">Grand Total:</span> {{ number_format($saleGrandTotal, 2) }}</div>
                <div><span class="font-semibold text-gray-800 dark:text-white">Paid:</span> {{ number_format($salePaid, 2) }}</div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Batch No</th>
                        <th class="px-4 py-3 text-left">Qty</th>
                        <th class="px-4 py-3 text-left">Unit Price</th>
                        <th class="px-4 py-3 text-left">Discount</th>
                        <th class="px-4 py-3 text-left">Tax</th>
                        <th class="px-4 py-3 text-left">Total</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm">
                    @forelse($sale->items as $item)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3">{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $item->productBatch->batch_no ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3">{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="px-4 py-3 text-red-500 dark:text-red-400">{{ number_format((float) $item->discount, 2) }}</td>
                            <td class="px-4 py-3 text-blue-500 dark:text-blue-400">{{ number_format((float) $item->tax, 2) }}</td>
                            <td class="px-4 py-3 font-semibold">{{ number_format((float) $item->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">No items found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @empty
        <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 text-center text-gray-500 dark:text-gray-400">
            No sales found for this customer.
        </div>
    @endforelse

    <div id="payDueModal" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">
                Pay Due — <span id="payDueInvoiceNo" class="text-green-600 dark:text-green-400"></span>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Outstanding due: <span id="payDueAmountLabel" class="font-semibold text-red-500 dark:text-red-400"></span>
            </p>

            <form id="payDueForm" method="POST" action="#">
                @csrf
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Amount to Pay
                </label>
                <input
                    type="number"
                    name="amount"
                    id="payDueAmountInput"
                    step="0.01"
                    min="0.01"
                    required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <p id="payDueAmountError" class="hidden text-xs text-red-500 dark:text-red-400 mt-1"></p>

                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" onclick="closePayDueModal()"
                            class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openPayDueModal(btn, invoiceNo, dueAmount) {
            document.getElementById('payDueInvoiceNo').innerText = invoiceNo;
            document.getElementById('payDueAmountLabel').innerText = dueAmount.toFixed(2);
            document.getElementById('payDueAmountInput').value = dueAmount.toFixed(2);
            document.getElementById('payDueAmountInput').max = dueAmount.toFixed(2);
            document.getElementById('payDueForm').action = btn.dataset.payUrl;
            document.getElementById('payDueModal').classList.remove('hidden');
        }

        function closePayDueModal() {
            document.getElementById('payDueModal').classList.add('hidden');
            document.getElementById('payDueForm').reset();
            document.getElementById('payDueAmountError').classList.add('hidden');
        }
    </script>
@endpush
