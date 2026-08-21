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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->string('purchase_no', 50)->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('user_id');
            $table->timestampTz('purchase_date')
                ->useCurrent();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->default(0);
            $table->string('payment_status', 20)
                ->default('UNPAID');
            $table->string('status', 20)
                ->default('COMPLETED');
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')
                ->useCurrent();

            // Foreign Keys
            $table->foreign('supplier_id')
                ->references('supplier_id')
                ->on('suppliers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

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

            // // Purchase Status
            // $table->check(
            //     "status IN (
            //         'DRAFT',
            //         'COMPLETED',
            //         'CANCELLED'
            //     )"
            // );
        });

        DB::statement("
        ALTER TABLE purchases
        ADD CONSTRAINT chk_purchase_payment_status
        CHECK (
            payment_status IN (
                'UNPAID',
                'PARTIAL',
                'PAID'
                )
            )
        ");

        DB::statement("
        ALTER TABLE purchases
        ADD CONSTRAINT chk_purchase_status
        CHECK (
            status IN (
                'DRAFT',
                'COMPLETED',
                'CANCELLED'
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
