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
        Schema::table('approvals', function (Blueprint $table) {
            // Jabatan validator saat approval
            $table->uuid('position_id')
                ->nullable()
                ->after('validator_user_id');

            // Assignment pejabat yang aktif saat approval
            $table->uuid('position_assignment_id')
                ->nullable()
                ->after('position_id');

            $table->foreign('position_id')
                ->references('id')
                ->on('positions')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('position_assignment_id')
                ->references('id')
                ->on('position_assignments')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropForeign(['position_assignment_id']);
            $table->dropForeign(['position_id']);

            $table->dropColumn([
                'position_assignment_id',
                'position_id'
            ]);
        });
    }
};
