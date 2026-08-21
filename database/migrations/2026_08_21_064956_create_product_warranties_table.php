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
        Schema::create('product_warranties', function (Blueprint $table) {
            $table->id('pw_id');
            $table->unsignedBigInteger('serial_id');
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->date('warranty_start');
            $table->date('warranty_end');
            $table->integer('warranty_period_months')->nullable();
            $table->text('terms')->nullable();
            $table->string('status', 30)
                ->default('ACTIVE');
            $table->timestampTz('created_at')
                ->useCurrent();

            // Serial Number
            $table->foreign('serial_id')
                ->references('serial_id')
                ->on('product_serials')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Sale
            $table->foreign('sale_id')
                ->references('sale_id')
                ->on('sales')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Warranty dates
            // $table->check(
            //     'warranty_end >= warranty_start'
            // );

            // // Status
            // $table->check(
            //     "status IN (
            //         'ACTIVE',
            //         'EXPIRED',
            //         'VOID'
            //     )"
            // );
        });

        DB::statement("
        ALTER TABLE product_warranties
        ADD CONSTRAINT chk_product_warranty_date
        CHECK (warranty_end >= warranty_start)
        ");

        DB::statement("
        ALTER TABLE product_warranties
        ADD CONSTRAINT chk_product_warranty_status
        CHECK (
            status IN (
                'ACTIVE',
                'EXPIRED',
                'VOID'
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warranties');
    }
};
