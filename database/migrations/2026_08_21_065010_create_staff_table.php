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
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->string('staff_code', 30)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name', 200);
            $table->unsignedInteger('gender_id')->nullable();
            $table->string('phone', 30);
            $table->string('email', 150)
                ->nullable();
            $table->text('address')
                ->nullable();
            $table->string('city', 100)
                ->nullable();
            $table->string('province', 100)
                ->nullable();
            $table->date('date_of_birth')
                ->nullable();
            $table->string('nationality', 50)
                ->nullable();
            $table->unsignedBigInteger('position_id');
            $table->date('hire_date');
            $table->date('resign_date')
                ->nullable();
            $table->decimal('salary', 14, 2)
                ->default(0);
            $table->string('employment_status', 30)
                ->default('ACTIVE');
            $table->text('photo')
                ->nullable();
            $table->string('emergency_contact_name', 150)
                ->nullable();
            $table->string('emergency_contact_phone', 30)
                ->nullable();
            $table->string('emergency_contact_relation', 50)
                ->nullable();
            $table->text('notes')
                ->nullable();
            $table->timestampsTz();

            // Gender
            $table->foreign('gender_id')
                ->references('gender_id')
                ->on('genders')
                ->cascadeOnUpdate()
                ->nullOnDelete();


            // Position
            $table->foreign('position_id')
                ->references('position_id')
                ->on('positions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            // Salary cannot be negative
            // $table->check('salary >= 0');

            // Employment Status
            // $table->check(
            //     "employment_status IN (
                    // 'ACTIVE',
                    // 'INACTIVE',
                    // 'ON_LEAVE',
                    // 'RESIGNED',
                    // 'TERMINATED'
            //     )"
            // );
        });

        DB::statement("
        ALTER TABLE staff
        ADD CONSTRAINT chk_staff_salary
        CHECK (salary >= 0)
        ");

        DB::statement("
        ALTER TABLE staff
        ADD CONSTRAINT chk_staff_status
        CHECK (
            employment_status IN (
                'ACTIVE',
                'INACTIVE',
                'ON_LEAVE',
                'RESIGNED',
                'TERMINATED'
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
