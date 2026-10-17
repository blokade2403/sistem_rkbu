<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('approval_id')
                ->constrained('approvals')
                ->cascadeOnDelete();

            $table->foreignUuid('workflow_instance_id')
                ->constrained('workflow_instances')
                ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignUuid('position_id')
                ->nullable()
                ->constrained('positions')
                ->nullOnDelete();

            $table->foreignUuid('position_assignment_id')
                ->nullable()
                ->constrained('position_assignments')
                ->nullOnDelete();

            $table->enum('aksi', [
                'submit',
                'approved',
                'revision',
                'rejected',
                'delegated',
                'cancel'
            ]);

            $table->text('catatan')->nullable();

            $table->timestamp('aksi_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
    }
};
