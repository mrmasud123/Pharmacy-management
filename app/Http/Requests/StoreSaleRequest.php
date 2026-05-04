<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ProductBatch;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
 
            'total' => 'required|numeric|min:0',
 
            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',
            'items.*.batch_id'   => 'required|exists:product_batches,id',

            'items.*.desc' => 'nullable|string|max:255',

            'items.*.qty'  => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',

            'items.*.discount' => 'nullable|numeric|min:0|max:100',
            'items.*.vat'      => 'nullable|numeric|min:0',

            'items.*.total' => 'required|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
        ];
    }
 
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $calculatedTotal = 0;

            foreach ($this->items as $index => $item) {
 
                $batch = ProductBatch::find($item['batch_id']);

                if (!$batch) continue;

                if ($batch->quantity < $item['qty']) {
                    $validator->errors()->add(
                        "items.$index.qty",
                        "Insufficient stock for selected batch."
                    );
                }
 
                $qty      = (float) $item['qty'];
                $rate     = (float) $item['rate'];
                $discount = (float) ($item['discount'] ?? 0);
                $vat      = (float) ($item['vat'] ?? 0);

                $subtotal      = $qty * $rate;
                $discountAmt   = $subtotal * ($discount / 100);
                $afterDiscount = $subtotal - $discountAmt;
                $vatAmt        = $afterDiscount * ($vat / 100);
                $total         = $afterDiscount + $vatAmt;
 
                if (round($total, 2) != round($item['total'], 2)) {
                    $validator->errors()->add(
                        "items.$index.total",
                        "Item total mismatch (possible manipulation)."
                    );
                }

                $calculatedTotal += $total;
            }
 
            if (round($calculatedTotal, 2) != round($this->total, 2)) {
                $validator->errors()->add(
                    'total',
                    'Grand total mismatch (invalid calculation).'
                );
            }

        });
    }

    // ── Custom Messages (Better UX) ─────────────────────
    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least one product.',
            'items.*.product_id.required' => 'Product is required.',
            'items.*.batch_id.required' => 'Batch is required.',
            'items.*.qty.min' => 'Quantity must be at least 1.',
        ];
    }
}