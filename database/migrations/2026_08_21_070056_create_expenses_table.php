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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestampTz('expense_date')
                ->useCurrent();
            $table->string('category', 100);
            $table->text('description')
                ->nullable();
            $table->decimal('amount', 14, 2);
            $table->timestampTz('created_at')
                ->useCurrent();

            // Foreign Key
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Amount must be greater than 0
            // $table->check('amount > 0');
        });

        DB::statement("
            ALTER TABLE expenses
            ADD CONSTRAINT chk_expense_amount
            CHECK (amount>=0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
