<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('code')->unique();
            $table->string('barcode')->nullable()->unique();

            
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('product_type_id')
                ->nullable()
                ->constrained('product_types')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Product Info
      
            $table->unsignedInteger('alert_quantity')->default(10);

            // Media
            $table->string('image')->nullable();

            // Timestamps
            $table->timestamps();

            // Optional: soft delete (recommended for admin panels)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
