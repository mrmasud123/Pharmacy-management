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
        Schema::table('sales', function (Blueprint $table) {

            $table->softDeletes();
            $table->decimal('total')->comment('Total amount without discount and tax')->change();
            $table->decimal('grand_total')->comment('Total amount wit discount and tax')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropSoftDeletes();

            // Remove comments if needed
            $table->string('total')->comment(null)->change();
            $table->decimal('grand_total')->comment(null)->change();
        });
    }
};
