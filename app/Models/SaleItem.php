<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $guarded = [];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    
    public function productBatch()
    {
        return $this->belongsTo(ProductBatch::class);
    }
    
 

    public function batch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
}
