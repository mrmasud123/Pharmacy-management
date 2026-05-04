<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = [];
    
    protected $with = ['media'];
    protected $appends = ['photo_url'];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('categories')
            ->singleFile();
    }
    
    public function getPhotoUrlAttribute()
    {
        return $this->getFirstMediaUrl('categories') ?: null;
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }
    
    // public function supplier()
    // {
    //     return $this->belongsTo(Supplier::class);
    // }
    
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
    
}
