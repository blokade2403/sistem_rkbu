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

            $table->uuid('current_step_id')
                ->nullable()
                ->after('workflow_id');

            $table->uuid('submitted_by')
                ->nullable()
                ->after('dokumen_type');

            $table->uuid('submitted_position_id')
                ->nullable()
                ->after('submitted_by');

            $table->uuid('submitted_assignment_id')
                ->nullable()
                ->after('submitted_position_id');

            $table->timestamp('submitted_at')
                ->nullable()
                ->after('submitted_assignment_id');

            $table->timestamp('finished_at')
                ->nullable()
                ->after('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_instances', function (Blueprint $table) {

            $table->dropColumn([
                'current_step_id',
                'submitted_by',
                'submitted_position_id',
                'submitted_assignment_id',
                'submitted_at',
                'finished_at'
            ]);
        });
    }
};
