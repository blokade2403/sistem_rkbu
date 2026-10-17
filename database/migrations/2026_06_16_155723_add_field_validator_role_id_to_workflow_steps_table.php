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
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->uuid('validator_role_id')
                ->nullable()
                ->after('role_id');

            $table->foreign('validator_role_id')
                ->references('id')
                ->on('validator_roles')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->dropForeign(['validator_role_id']);
            $table->dropColumn('validator_role_id');
        });
    }
};
