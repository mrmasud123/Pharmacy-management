
@extends('layouts.app')

@section('vendor-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/assets/js/sale-edit.js'])
@endsection

@section('content')

    <x-common.page-breadcrumb
        :pageTitle="[
        ['name' => 'Collections', 'link'=> '/collections'],
        ['name' => 'Edit Invoice', 'link' => '#']
    ]"
    />

    <div class="bg-white dark:bg-white/5 rounded-2xl shadow-lg p-6 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Edit Invoice: <span class="text-amber-600 dark:text-amber-400">{{ $sale->invoice_no }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Customer: {{ $sale->customer->name ?? 'N/A' }} · {{ $sale->customer->phone ?? '' }}
                </p>
            </div>

            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-1 px-3 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition-colors">
                ← Back
            </a>
        </div>

        <form id="saleEditForm" method="POST" action="{{ route('admin.sales.update', $sale->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sale Date</label>
                    <input type="date" name="sale_date" value="{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="completed" {{ $sale->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ $sale->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ $sale->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paid Amount</label>
                    <input type="number" step="0.01" min="0" name="paid" id="paidInput" value="{{ $sale->paid }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p id="paidError" class="hidden text-xs text-red-500 dark:text-red-400 mt-1"></p>
                </div>
            </div>
            <div class="overflow-x-auto mb-2">
                <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden" id="itemsTable">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs uppercase">
                    <tr>
                        <th class="px-3 py-3 text-left">Product</th>
                        <th class="px-3 py-3 text-left">Batch</th>
                        <th class="px-3 py-3 text-left w-20">Qty</th>
                        <th class="px-3 py-3 text-left w-28">Unit Price</th>
                        <th class="px-3 py-3 text-left w-24">Discount %</th>
                        <th class="px-3 py-3 text-left w-24">Tax %</th>
                        <th class="px-3 py-3 text-right w-28">Line Total</th>
                        <th class="px-3 py-3 w-10"></th>
                    </tr>
                    </thead>
                    <tbody id="itemsBody" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($sale->items as $i => $item)
                        <tr class="item-row" data-index="{{ $i }}">
                            <td class="px-3 py-2">
                                <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $item->product_id }}">
                                <input type="hidden" name="items[{{ $i }}][batch_id]" value="{{ $item->product_batch_id }}">
                                <span class="text-gray-800 dark:text-white">{{ $item->product->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-3 py-2 text-gray-500 dark:text-gray-400">
                                {{ $item->productBatch->batch_no ?? 'N/A' }}
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="1" step="1" name="items[{{ $i }}][qty]" value="{{ $item->quantity }}"
                                       class="qty-input w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="0" step="0.01" name="items[{{ $i }}][rate]" value="{{ $item->unit_price }}"
                                       class="rate-input w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="0" max="100" step="0.01" name="items[{{ $i }}][discount]"
                                       value="{{ $item->quantity * $item->unit_price > 0 ? round(($item->discount / ($item->quantity * $item->unit_price)) * 100, 2) : 0 }}"
                                       class="discount-input w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="0" max="100" step="0.01" name="items[{{ $i }}][vat]"
                                       value="{{ ($item->unit_price * $item->quantity - $item->discount) > 0 ? round(($item->tax / ($item->unit_price * $item->quantity - $item->discount)) * 100, 2) : 0 }}"
                                       class="vat-input w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </td>
                            <td class="px-3 py-2 text-right font-semibold line-total" data-raw="{{ $item->total }}">
                                {{ number_format((float) $item->total, 2) }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Remove">
                                    <span class="iconify" data-icon="lucide:x" data-width="16"></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                Adding new product lines isn't supported here yet — only existing line quantities, rates, discount %, and tax % can be edited.
            </p>

            <div class="flex justify-end">
                <div class="w-full md:w-80 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Subtotal</span>
                        <span id="summarySubtotal" class="font-medium text-gray-800 dark:text-white">{{ number_format($sale->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Discount</span>
                        <span id="summaryDiscount" class="font-medium text-red-500 dark:text-red-400">{{ number_format($sale->discount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Tax</span>
                        <span id="summaryTax" class="font-medium text-blue-500 dark:text-blue-400">{{ number_format($sale->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-semibold text-gray-800 dark:text-white border-t border-gray-200 dark:border-gray-700 pt-2">
                        <span>Grand Total</span>
                        <span id="summaryGrandTotal">{{ number_format($sale->grand_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Paid</span>
                        <span id="summaryPaid" class="font-medium text-green-500 dark:text-green-400">{{ number_format($sale->paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-semibold border-t border-gray-200 dark:border-gray-700 pt-2">
                        <span class="text-gray-800 dark:text-white">Due</span>
                        <span id="summaryDue" class="text-red-500 dark:text-red-400">{{ number_format($sale->due, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ url()->previous() }}"
                   class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 text-sm font-medium rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white transition-colors">
                    Save Changes
                </button>
            </div>

        </form>
    </div>

@endsection
