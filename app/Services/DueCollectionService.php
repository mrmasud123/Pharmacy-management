<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\ProductBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DueCollectionService
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function dueCollection(Request $request, Sale $sale)
    {
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $sale->due,
            ],
            'payment_method' => 'nullable|string|max:50',
            'reference_no'   => 'nullable|string|max:100',
        ]);

        return DB::transaction(function () use ($request, $sale) {

            $amount = (float) $request->amount;

            $sale->paid += $amount;
            $sale->due  -= $amount;
            $sale->save();

            $customer = $sale->customer()->lockForUpdate()->first();

            if ($customer) {
                $this->transactionService->record(
                    customer: $customer,
                    type: 'due_collection',
                    direction: 'credit',
                    amount: $amount,
                    transactionable: $sale,
                    paymentMethod: $request->payment_method,
                    referenceNo: $request->reference_no,
                    note: "Due collected for invoice {$sale->invoice_no}"
                );
            }

            return $sale;
        });
    }

    public function updateDueCollection($request, $sale)
    {
        $validated = $request->validate([
            'sale_date'           => 'required|date',
            'status'              => 'required|in:completed,pending,cancelled',
            'paid'                => 'required|numeric|min:0',
            'items'               => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.batch_id'   => 'required|exists:product_batches,id',
            'items.*.qty'        => 'required|numeric|min:1',
            'items.*.rate'       => 'required|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0|max:100',
            'items.*.vat'        => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($validated, $sale) {

            $subtotal = 0; $totalDiscount = 0; $totalTax = 0;
            $lines = [];

            foreach ($validated['items'] as $itemData) {
                $qty     = (float) $itemData['qty'];
                $rate    = (float) $itemData['rate'];
                $discPct = (float) ($itemData['discount'] ?? 0);
                $vatPct  = (float) ($itemData['vat'] ?? 0);

                $lineSubtotal  = $qty * $rate;
                $discountAmt   = $lineSubtotal * ($discPct / 100);
                $afterDiscount = $lineSubtotal - $discountAmt;
                $vatAmt        = $afterDiscount * ($vatPct / 100);
                $lineTotal     = $afterDiscount + $vatAmt;

                $subtotal      += $lineSubtotal;
                $totalDiscount += $discountAmt;
                $totalTax      += $vatAmt;

                $lines[] = compact('itemData', 'qty', 'rate', 'discountAmt', 'vatAmt', 'lineTotal');
            }

            $grandTotal = $subtotal - $totalDiscount + $totalTax;
            $paid = (float) $validated['paid'];

            if ($paid > $grandTotal) {
                return response()->json([
                    'message' => 'Paid amount cannot exceed grand total (' . number_format($grandTotal, 2) . ').',
                ], 422);
            }

            $due = $grandTotal - $paid;

            foreach ($sale->items as $oldItem) {
                ProductBatch::where('id', $oldItem->product_batch_id)
                    ->lockForUpdate()
                    ->increment('quantity', $oldItem->quantity);

                StockMovement::create([
                    'product_id'       => $oldItem->product_id,
                    'product_batch_id' => $oldItem->product_batch_id,
                    'type'             => 'sale_edit_reversal',
                    'quantity'         => $oldItem->quantity,
                    'unit_price'       => $oldItem->unit_price,
                    'reference'        => $sale->invoice_no,
                ]);
            }
            $sale->items()->delete();

            foreach ($lines as $line) {
                $batch = ProductBatch::lockForUpdate()->findOrFail($line['itemData']['batch_id']);

                if ($batch->quantity < $line['qty']) {
                    throw new \Exception("Insufficient stock for batch ID {$batch->id}");
                }

                $batch->decrement('quantity', $line['qty']);

                SaleItem::create([
                    'sale_id'          => $sale->id,
                    'product_id'       => $line['itemData']['product_id'],
                    'product_batch_id' => $line['itemData']['batch_id'],
                    'quantity'         => $line['qty'],
                    'unit_price'       => $line['rate'],
                    'discount'         => round($line['discountAmt'], 2),
                    'tax'              => round($line['vatAmt'], 2),
                    'total'            => round($line['lineTotal'], 2),
                ]);

                StockMovement::create([
                    'product_id'       => $line['itemData']['product_id'],
                    'product_batch_id' => $line['itemData']['batch_id'],
                    'type'             => 'sale_edit',
                    'quantity'         => -$line['qty'],
                    'unit_price'       => $line['rate'],
                    'reference'        => $sale->invoice_no,
                ]);
            }

            $oldDue = (float) $sale->due;

            $sale->update([
                'sale_date'   => $validated['sale_date'],
                'status'      => $validated['status'],
                'total'       => round($subtotal, 2),
                'discount'    => round($totalDiscount, 2),
                'tax'         => round($totalTax, 2),
                'grand_total' => round($grandTotal, 2),
                'paid'        => round($paid, 2),
                'due'         => round($due, 2),
            ]);

            if ($sale->customer_id) {
                $customer = Customer::lockForUpdate()->findOrFail($sale->customer_id);

                $delta = $due - $oldDue;

                if ($delta !== 0.0) {
                    $this->transactionService->record(
                        customer: $customer,
                        type: 'adjustment',
                        direction: $delta > 0 ? 'debit' : 'credit',
                        amount: abs($delta),
                        transactionable: $sale,
                        note: "Invoice {$sale->invoice_no} edited — due changed from "
                        . number_format($oldDue, 2) . ' to ' . number_format($due, 2)
                    );
                }
            }
            return $sale;
        });
    }
}
