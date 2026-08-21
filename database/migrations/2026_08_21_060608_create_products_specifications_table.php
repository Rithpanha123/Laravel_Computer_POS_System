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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id('ps_id');
            $table->unsignedBigInteger('product_id')->unique();
            $table->string('cpu', 150)->nullable();
            $table->string('ram', 100)->nullable();
            $table->string('storage', 150)->nullable();
            $table->string('gpu', 150)->nullable();
            $table->string('screen_size', 50)->nullable();
            $table->string('screen_resolution', 100)->nullable();
            $table->string('operating_system', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->text('other_specs')->nullable();
            $table->timestampsTz();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_specifications');
    }
};
