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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id('sale_item_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('serial_id')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 14, 2);
            $table->decimal('discount', 14, 2)
                ->default(0);
            $table->decimal('total', 14, 2);

            // Sale
            $table->foreign('sale_id')
                ->references('sale_id')
                ->on('sales')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Product
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Serial Number
            $table->foreign('serial_id')
                ->references('serial_id')
                ->on('product_serials')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Quantity
            // $table->check('quantity > 0');

            // // Price
            // $table->check('unit_price >= 0');
        });

        DB::statement("
            ALTER TABLE sale_items
            ADD CONSTRAINT chk_sale_item_quantity
            CHECK (quantity >= 0)
        ");

        DB::statement("
            ALTER TABLE sale_items
            ADD CONSTRAINT chk_sale_item_unit_price
            CHECK (unit_price >= 0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
