<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
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
}
