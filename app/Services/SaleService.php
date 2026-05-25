<?php

namespace App\Services;

use App\Models\ProductBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
    
            $subtotal       = 0;
            $totalDiscount  = 0;
            $totalTax       = 0;
    
            $preparedItems = [];
     
            foreach ($data['items'] as $item) {
    
                $batch = ProductBatch::lockForUpdate()->findOrFail($item['batch_id']);
    
                $qty      = (float) $item['qty'];
                $rate     = (float) $item['rate'];
                $discount = (float) ($item['discount'] ?? 0); 
                $vat      = (float) ($item['vat'] ?? 0);      
    
                if ($batch->quantity < $qty) {
                    throw new \Exception("Insufficient stock for batch ID {$batch->id}");
                }
    
                $lineSubtotal  = $qty * $rate;
    
                $discountAmt   = $lineSubtotal * ($discount / 100);
                $afterDiscount = $lineSubtotal - $discountAmt;
    
                $vatAmt        = $afterDiscount * ($vat / 100);
    
                $lineTotal     = $afterDiscount + $vatAmt;
    
                $subtotal      += $lineSubtotal;
                $totalDiscount += $discountAmt;
                $totalTax      += $vatAmt;
    
                $preparedItems[] = [
                    'product_id'       => $item['product_id'],
                    'batch_id'         => $batch->id,
                    'qty'              => $qty,
                    'rate'             => $rate,
                    'discount_amt'     => $discountAmt,
                    'tax_amt'          => $vatAmt,
                    'total'            => $lineTotal,
                ];
            }
    
            $grandTotal = $subtotal - $totalDiscount + $totalTax;
    
            $paid = (float) ($data['paid'] ?? 0);
            $due  = $grandTotal - $paid;
    
            $sale = Sale::create([
                'invoice_no'  => $this->generateInvoice(),
    
                'total'       => round($subtotal, 2),
                'discount'    => round($totalDiscount, 2),
                'tax'         => round($totalTax, 2),
                'grand_total' => round($grandTotal, 2),
    
                'paid'        => round($paid, 2),
                'due'         => round($due, 2),
    
                'status'      => 'completed',
                'customer_id' => $data['customer_id'] ?? null,
                'sale_date'   => $data['sale_date'] ?? date('Y-m-d'),
            ]);
    
            foreach ($preparedItems as $item) {
    
                SaleItem::create([
                    'sale_id'          => $sale->id,
                    'product_id'       => $item['product_id'],
                    'product_batch_id' => $item['batch_id'],
                    'quantity'         => $item['qty'],
                    'unit_price'       => $item['rate'],
                    'discount'         => $item['discount_amt'],
                    'tax'              => $item['tax_amt'],
                    'total'            => $item['total'],
                ]);
    
                $batch = ProductBatch::find($item['batch_id']);
                $batch->decrement('quantity', $item['qty']);
    
                StockMovement::create([
                    'product_id'       => $item['product_id'],
                    'product_batch_id' => $item['batch_id'],
                    'type'             => 'sale',
                    'quantity'         => -$item['qty'],
                    'unit_price'       => $item['rate'],
                    'reference'        => $sale->invoice_no,
                ]);
            }
    
            return $sale;
        });
    }
    private function generateInvoice(): string
    {
        $date = now()->format('Ymd');

        $lastSale = Sale::whereDate('created_at', now())
            ->latest('id')
            ->first();

        $number = $lastSale ? ((int) substr($lastSale->invoice_no, -4)) + 1 : 1;

        return 'INV-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}