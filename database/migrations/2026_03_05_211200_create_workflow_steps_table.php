<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id');
            $table->uuid('position_id');
            $table->foreignUuid('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->integer('step_order');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_pptk_step')->default(false);
            $table->foreign('position_id')->references('id')->on('positions')->cascadeOnDelete();
            $table->foreign('workflow_id')->references('id')->on('workflows')->cascadeOnDelete();
            /* contoh role: KSP, KABAG, DIREKTUR
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
