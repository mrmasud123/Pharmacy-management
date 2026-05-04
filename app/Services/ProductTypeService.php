<?php

namespace App\Services;

use App\Models\ProductType;

class ProductTypeService
{
    public function __construct()
    {
        // Initialize any dependencies or configurations here
    }
    
    public function store(array $data): ProductType
    {
        return ProductType::create($data);
    }
    
    public function update(ProductType $productType, array $data): ProductType
    {
        $productType->update($data);
        return $productType;
    }
}