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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id('repair_id');
            $table->string('repair_no', 50)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('device_name', 200);
            $table->string('serial_number', 150)->nullable();
            $table->text('problem_description');
            $table->text('diagnosis')->nullable();
            $table->decimal('estimated_cost', 14, 2)
                ->default(0);
            $table->decimal('final_cost', 14, 2)
                ->default(0);
            $table->string('status', 30)
                ->default('RECEIVED');
            $table->timestampTz('received_at')
                ->useCurrent();
            $table->timestampTz('completed_at')
                ->nullable();
            $table->text('notes')->nullable();

            // Customer
            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Technician
            $table->foreign('staff_id')
                ->references('staff_id')
                ->on('staff')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Repair Status
            // $table->check(
            //     "status IN (
            //         'RECEIVED',
            //         'DIAGNOSING',
            //         'WAITING_PART',
            //         'REPAIRING',
            //         'COMPLETED',
            //         'DELIVERED',
            //         'CANCELLED'
            //     )"
            // );

            // Costs
            // $table->check('estimated_cost >= 0');
            // $table->check('final_cost >= 0');
        });

        DB::statement("
        ALTER TABLE repairs
        ADD CONSTRAINT chk_repair_status
        CHECK (
            status IN (
                'RECEIVED',
                'DIAGNOSING',
                'WAITING_PART',
                'REPAIRING',
                'COMPLETED',
                'DELIVERED',
                'CANCELLED'
                )
            )
        ");

        DB::statement("
            ALTER TABLE repairs
            ADD CONSTRAINT chk_repair_estimated_cost
            CHECK (estimated_cost>=0)
        ");

        DB::statement("
            ALTER TABLE repairs
            ADD CONSTRAINT chk_repair_final_cost
            CHECK (final_cost>=0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
