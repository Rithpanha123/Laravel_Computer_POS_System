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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('discount', 14, 2)
                ->default(0);
            $table->decimal('total', 14, 2);
            
            // Foreign Key: Purchase
            $table->foreign('purchase_id')
                ->references('purchase_id')
                ->on('purchases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Foreign Key: Product
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Quantity
            // $table->check('quantity > 0');

            // Cost
            // $table->check('unit_cost >= 0');
        });

        DB::statement("
            ALTER TABLE purchase_items
            ADD CONSTRAINT chk_purchase_items_quantity
            CHECK (quantity >= 0)
        ");

        DB::statement("
            ALTER TABLE purchase_items
            ADD CONSTRAINT chk_purchase_items_unit_cost
            CHECK (unit_cost >= 0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
