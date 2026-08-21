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
        Schema::create('product_serials', function (Blueprint $table) {
            $table->id('serial_id');
            $table->unsignedBigInteger('product_id');
            $table->string('serial_number', 150)->unique();
            $table->string('status', 30)->default('IN_STOCK');
            $table->timestampTz('created_at')
                ->useCurrent();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // $table->check(
            //     "status IN (
            //         'IN_STOCK',
            //         'SOLD',
            //         'RETURNED',
            //         'WARRANTY',
            //         'DAMAGED'
            //     )"
            // );
        });

        DB::statement("
        ALTER TABLE product_serials
        ADD CONSTRAINT chk_product_serial_status
        CHECK (
            status IN (
                'IN_STOCK',
                'SOLD',
                'RETURNED',
                'WARRANTY',
                'DAMAGED'
                )
            )
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_serials');
    }
};
