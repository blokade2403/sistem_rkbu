<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE workflow_instances
            MODIFY COLUMN status ENUM(
                'draft',
                'proses',
                'revision',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'draft'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE workflow_instances
            MODIFY COLUMN status ENUM(
                'draft',
                'proses',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'draft'
        ");
    }
};
