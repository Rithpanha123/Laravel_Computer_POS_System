<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('sku', 50)->unique();
            $table->string('barcode', 255)->nullable()->unique();
            $table->string('product_name', 200);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->text('photo')->nullable();
            $table->text('description')->nullable();
            $table->decimal('cost_price', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->boolean('is_serialized')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
            // Foreign Keys
            $table->foreign('category_id')
                ->references('cate_id')
                ->on('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('brand_id')
                ->references('brand_id')
                ->on('brands')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Check Constraints
            // $table->check('cost_price >= 0');
            // $table->check('selling_price >= 0');
            // $table->check('stock_quantity >= 0');
            // $table->check('reorder_level >= 0');
        });

        // PostgreSQL CHECK constraints
        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_cost_price
            CHECK (cost_price >= 0)
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_selling_price
            CHECK (selling_price >= 0)
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_stock
            CHECK (stock_quantity >= 0)
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_reorder
            CHECK (reorder_level >= 0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
