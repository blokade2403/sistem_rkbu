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
        Schema::table('approvals', function (Blueprint $table) {

            $table->timestamp('received_at')
                ->nullable()
                ->after('status');

            $table->timestamp('closed_at')
                ->nullable()
                ->after('approved_at');

            $table->integer('sequence')
                ->default(1)
                ->after('closed_at');

            $table->boolean('is_current')
                ->default(true)
                ->after('sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {

            $table->dropColumn([
                'received_at',
                'closed_at',
                'sequence',
                'is_current'
            ]);
        });
    }
};
