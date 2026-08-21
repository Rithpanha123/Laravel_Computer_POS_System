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
        Schema::create('repair_items', function (Blueprint $table) {
            $table->id('ri_id');
            $table->unsignedBigInteger('repair_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('description', 255);
            $table->integer('quantity')
                ->default(1);
            $table->decimal('unit_price', 14, 2)
                ->default(0);
            $table->decimal('total', 14, 2)
                ->default(0);

            // Repair
            $table->foreign('repair_id')
                ->references('repair_id')
                ->on('repairs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Product / Spare Part
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Quantity
            // $table->check('quantity > 0');

            // // Price
            // $table->check('unit_price >= 0');
        });

        DB::statement("
            ALTER TABLE repair_items
            ADD CONSTRAINT chk_repair_item_quantity
            CHECK (quantity>=0)
        ");
        DB::statement("
            ALTER TABLE repair_items
            ADD CONSTRAINT chk_repair_item_unit_price
            CHECK (unit_price>=0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_items');
    }
};
