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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id('rp_id');

            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');

            $table->timestampsTz();

            $table->foreign('role_id')
                ->references('role_id')
                ->on('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('permission_id')
                ->references('permission_id')
                ->on('permissions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(
                ['role_id', 'permission_id'],
                'role_permissions_role_permission_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
