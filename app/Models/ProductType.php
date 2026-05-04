<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $guarded = [];
    
    //slug
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productType) {
            if (empty($productType->slug)) {
                $productType->slug = Str::slug($productType->name);
            }
        });

        static::updating(function ($productType) {
            if ($productType->isDirty('name')) {
                $productType->slug = Str::slug($productType->name);
            }
        });
    }
    
    
}
