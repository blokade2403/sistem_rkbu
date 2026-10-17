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
        Schema::create('user_validator_roles', function (Blueprint $table) {
            $table->char('id', 36)->primary();

            $table->char('user_id', 36);

            $table->char('validator_role_id', 36);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table
                ->foreign('validator_role_id')
                ->references('id')
                ->on('validator_roles')
                ->cascadeOnDelete();

            $table->unique(['user_id', 'validator_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_validator_roles');
    }
};
