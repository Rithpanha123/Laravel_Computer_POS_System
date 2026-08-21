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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sale_id');
            $table->string('invoice_no', 50)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestampTz('sale_date')
                ->useCurrent();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->default(0);
            $table->string('payment_status', 20)
                ->default('PAID');
            $table->string('status', 20)
                ->default('COMPLETED');
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')
                ->useCurrent();

            // Customer
            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // User / Cashier
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Payment Status
            // $table->check(
            //     "payment_status IN (
            //         'UNPAID',
            //         'PARTIAL',
            //         'PAID'
            //     )"
            // );

            // // Sale Status
            // $table->check(
            //     "status IN (
            //         'DRAFT',
            //         'COMPLETED',
            //         'CANCELLED',
            //         'RETURNED'
            //     )"
            // );
        });

        DB::statement("
        ALTER TABLE sales
        ADD CONSTRAINT chk_sales_payment_status
        CHECK (
            payment_status IN (
                'UNPAID',
                'PARTIAL',
                'PAID'
                )
            )
        ");

        DB::statement("
        ALTER TABLE sales
        ADD CONSTRAINT chk_sales_status
        CHECK (
            status IN (
                'DRAFT',
                'COMPLETED',
                'CANCELED',
                'RETURNED'
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
