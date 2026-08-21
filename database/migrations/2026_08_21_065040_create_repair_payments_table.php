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
        Schema::create('repair_payments', function (Blueprint $table) {
            $table->id('rp_id');
            $table->unsignedBigInteger('repair_id');
            $table->string('payment_method', 30);
            $table->decimal('amount', 14, 2);
            $table->timestampTz('payment_date')
                ->useCurrent();
            $table->string('reference_no', 100)
                ->nullable();
            $table->text('notes')
                ->nullable();

            // Repair
            $table->foreign('repair_id')
                ->references('repair_id')
                ->on('repairs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Amount
            // $table->check('amount > 0');

            // // Payment Method
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
        });

        DB::statement("
            ALTER TABLE repair_payments
            ADD CONSTRAINT chk_repair_payment_amount
            CHECK (amount>=0)
        ");

        DB::statement("
        ALTER TABLE repair_payments
        ADD CONSTRAINT chk_repair_payment_method
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_payments');
    }
};
