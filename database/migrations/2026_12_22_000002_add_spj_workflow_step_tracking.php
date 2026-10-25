<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->uuid('pengadaan_workflow_step_id')->nullable()->after('pengadaan_step');
            $table->index('pengadaan_workflow_step_id', 'spjs_pengadaan_workflow_step_idx');
        });

        Schema::create('pengadaan_workflow_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spj_id');
            $table->uuid('workflow_step_id');
            $table->uuid('user_id')->nullable();
            $table->string('status', 24);
            $table->text('catatan')->nullable();
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();
            $table->unique(['spj_id', 'workflow_step_id'], 'pengadaan_approval_spj_step_unique');
            $table->foreign('spj_id')->references('id')->on('spjs')->cascadeOnDelete();
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaan_workflow_approvals');
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropIndex('spjs_pengadaan_workflow_step_idx');
            $table->dropColumn('pengadaan_workflow_step_id');
        });
    }
};
