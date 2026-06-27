<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE stock_movements MODIFY type ENUM(
            'sale',
            'purchase',
            'adjustment',
            'return',
            'sale_edit',
            'sale_edit_reversal'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stock_movements MODIFY type ENUM(
            'sale',
            'purchase',
            'adjustment',
            'return'
        ) NOT NULL");
    }
};
