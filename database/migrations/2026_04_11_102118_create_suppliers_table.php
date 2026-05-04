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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('company_name')->nullable();

            // Contact Info
            $table->string('email')->nullable()->index();
            $table->string('phone', 20)->index();

            // Financial
            $table->decimal('opening_balance', 12, 2)->default(0);

            // Address
            $table->text('address')->nullable();

            // Status
            $table->boolean('status')->default(true); // active/inactive

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
