<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];
    
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
