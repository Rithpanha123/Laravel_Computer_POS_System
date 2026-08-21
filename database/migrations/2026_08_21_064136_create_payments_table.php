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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->string('payment_method', 30);
            $table->decimal('amount', 14, 2);
            $table->timestampTz('payment_date')
                ->useCurrent();
            $table->string('reference_no', 100)->nullable();
            $table->text('notes')->nullable();

            // Sale
            $table->foreign('sale_id')
                ->references('sale_id')
                ->on('sales')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Purchase
            $table->foreign('purchase_id')
                ->references('purchase_id')
                ->on('purchases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Amount
            // $table->check('amount > 0');

            // Payment Method
            // $table->check(
            //     "payment_method IN (
            //         'CASH',
            //         'ABA',
            //         'ACCOUNTPAY',
            //         'CARD',
            //         'BANK_TRANSFER',
            //         'OTHER'
            //     )"
            // );

            // // Must belong to either Sale OR Purchase
            // $table->check(
            //     "(sale_id IS NOT NULL AND purchase_id IS NULL)
            //      OR
            //      (sale_id IS NULL AND purchase_id IS NOT NULL)"
            // );
        });

        DB::statement("
        ALTER TABLE payments
        ADD CONSTRAINT chk_payment_amount
        CHECK (amount >= 0)
        ");
        
        DB::statement("
        ALTER TABLE payments
        ADD CONSTRAINT chk_payment_method
        CHECK (
            payment_method IN (
                'CASH',
                'ABA',
                'ACCOUNTPAY',
                'CARD',
                'BANK_TRANSFER',
                'OTHER'
                )
            )
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT chk_payment_reference
            CHECK (
                (sale_id IS NOT NULL AND purchase_id IS NULL)
                OR
                (sale_id IS NULL AND purchase_id IS NOT NULL)
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
