<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function update(Category $category, array $data): Category
    { 
        $category->update([
            'name'      => $data['category_name'],
            'code'      => $data['category_code'] ?? null,
            'is_active' => $data['category_status'],
        ]);
 
        if (request()->hasFile('category_image')) {
            $category->clearMediaCollection('categories');

            $category->addMediaFromRequest('category_image')
                     ->toMediaCollection('categories');
        }

        return $category;
    }
}


?>