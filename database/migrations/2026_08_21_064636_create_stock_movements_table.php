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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id('stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->string('movement_type', 30);
            $table->integer('quantity');
            $table->string('reference_type', 30)->nullable();
            $table->text('note')->nullable();
            $table->timestampTz('created_at')
                ->useCurrent();

            // Product
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // User
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Movement Type
            // $table->check(
            //     "movement_type IN (
            //         'PURCHASE',
            //         'SALE',
            //         'SALE_RETURN',
            //         'PURCHASE_RETURN',
            //         'ADJUSTMENT_IN',
            //         'ADJUSTMENT_OUT',
            //         'DAMAGE'
            //     )"
            // );

            // // Quantity
            // $table->check('quantity > 0');
        });

        DB::statement("
        ALTER TABLE stock_movements
        ADD CONSTRAINT chk_stock_movement_type
        CHECK (
            movement_type IN (
                'PURCHASE',
                'SALE',
                'SALE_RETURN',
                'PURCHASE_RETURN',
                'ADJUSTMENT_IN',
                'ADJUSTMENT_OUT',
                'DAMAGE'
                )
            )
        ");

        DB::statement("
            ALTER TABLE stock_movements
            ADD CONSTRAINT chk_stock_movements_quantity
            CHECK (quantity >= 0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
