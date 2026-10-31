<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Deliberately no FK: audit history must survive asset deletion.
            $table->uuid('asset_id');
            $table->uuid('asset_unit_id')->nullable();
            $table->string('asset_name');
            $table->string('asset_register')->nullable();
            $table->string('action', 60);
            $table->text('description')->nullable();
            $table->json('before_data')->nullable();
            $table->json('after_data')->nullable();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['asset_id', 'created_at']);
            $table->index(['asset_unit_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
