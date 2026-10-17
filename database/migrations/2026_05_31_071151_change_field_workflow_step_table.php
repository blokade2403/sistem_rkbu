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
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');

            $table->string('level_jabatan')->after('workflow_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->integer('position_id')->unsigned()->after('workflow_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->dropColumn('level_jabatan');
        });
    }
};
