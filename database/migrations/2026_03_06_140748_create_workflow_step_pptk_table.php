<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workflow_step_pptk', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table
                ->foreignUuid('workflow_step_id')
                ->constrained('workflow_steps')
                ->cascadeOnDelete();

            $table
                ->foreignUuid('pptk_kategori_id')
                ->constrained('pptk_kategoris')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_step_pptk');
    }
};
