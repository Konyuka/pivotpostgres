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
        DB::statement('ALTER TABLE invoice_items ALTER COLUMN item_tax_type TYPE INT USING item_tax_type::integer');
        DB::statement('ALTER TABLE invoice_items ALTER COLUMN item_tax_rate TYPE DECIMAL(5,2) USING item_tax_rate::decimal');
        Schema::table('invoice_items', function (Blueprint $table) {
            // $table->integer('item_tax_type')->default(0)->change();
            // $table->decimal('item_tax_rate', 5, 2)->change();
            $table->integer('item_qty')->default(0)->change();
            $table->decimal('item_unit_price',10,2)->change();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('item_tax_type', 191);
            $table->string('item_tax_rate', 191);
            $table->bigInteger('item_qty')->default(0);
            $table->bigInteger('item_unit_price');
        });
    }
};
