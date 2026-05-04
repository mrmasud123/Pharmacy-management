<?php


namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class BrandService
{
    public function create(array $data): Brand
    {
        return Brand::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'status' => $data['status'],
            'created_by' => Auth::id(),
        ]);
    }

    public function update(Brand $brand, array $data): Brand
    {
        $brand->update([
            'name' => $data['name'],
            'code' => $data['code'],
            'status' => $data['status'],
        ]);

        return $brand;
    }
}