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

            /**
             * Jenis validator
             * hierarchy = mengikuti hirarki organisasi
             * position  = jabatan tertentu
             * role      = role tertentu
             * user      = user tertentu
             */
            $table->enum('validator_type', [
                'hierarchy',
                'position',
                'role',
                'user'
            ])
                ->default('hierarchy')
                ->after('workflow_id');

            /**
             * Berapa level hirarki
             * 1 = atasan langsung
             * 2 = atasan dari atasan
             * dst
             */
            $table->unsignedInteger('hierarchy_level')
                ->default(1)
                ->nullable()
                ->after('validator_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {

            $table->dropColumn([
                'validator_type',
                'hierarchy_level'
            ]);
        });
    }
};
