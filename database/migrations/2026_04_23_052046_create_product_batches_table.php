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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();

      
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();

      
            $table->string('batch_no')->nullable(); 
            $table->date('expire_date');

      
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('sales_price', 10, 2);

    
            $table->integer('quantity')->default(0);

            $table->timestamps();

    
            $table->index(['product_id', 'expire_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
