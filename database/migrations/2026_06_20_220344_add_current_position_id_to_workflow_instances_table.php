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
        Schema::table('workflow_instances', function (Blueprint $table) {
            $table->uuid('current_position_id')
                ->nullable()
                ->after('workflow_id');

            $table->foreign('current_position_id')
                ->references('id')
                ->on('positions')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_instances', function (Blueprint $table) {
            $table->dropForeign(['current_position_id']);

            $table->dropColumn('current_position_id');
        });
    }
};
