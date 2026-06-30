<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    //Batch product creation with stock movement

    public function addToBatch(array $data, int $productId): ProductBatch
    {
        return DB::transaction(function () use ($data, $productId) {


            if (empty($data['batch_no'])) {
                $data['batch_no'] = $this->generateBatchNo($productId);
            }

            // Create batch
            $batch = ProductBatch::create([
                'product_id'      => $productId,
                'supplier_id'     => $data['supplier_id'] ?? null,
                'unit_id'         => $data['unit_id'] ?? null,
                'batch_no'        => $data['batch_no'],
                'expire_date'     => $data['expire_date'],
                'purchase_price'  => $data['purchase_price'],
                'sales_price'     => $data['sales_price'],
                'quantity'        => $data['quantity'],
            ]);

            // Record stock movement
            StockMovement::create([
                'product_id'        => $productId,
                'product_batch_id'  => $batch->id,
                'type'              => 'purchase',
                'quantity'          => $data['quantity'], // positive
                'unit_price'        => $data['purchase_price'],
                'reference'         => 'BATCH-' . $batch->id,
            ]);

            return $batch;
        });
    }

    private function generateBatchNo(int $productId): string
    {
        return 'B-' . $productId . '-' . strtoupper(Str::random(5));
    }
}
