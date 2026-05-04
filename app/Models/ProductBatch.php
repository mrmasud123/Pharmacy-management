<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    protected $guarded = [];
    
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
