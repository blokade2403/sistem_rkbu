<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('dokumen_perencanaans') || !Schema::hasColumn('dokumen_perencanaans', 'created_by')) {
            return;
        }

        // The previous migration created this as BIGINT even though users.id is a UUID.
        // Clear incompatible values before changing the column type and restoring its FK.
        DB::table('dokumen_perencanaans')->update(['created_by' => null]);

        $createdByForeignKey = collect(Schema::getForeignKeys('dokumen_perencanaans'))
            ->first(fn (array $foreignKey) => in_array('created_by', $foreignKey['columns'], true));

        if ($createdByForeignKey) {
            Schema::table('dokumen_perencanaans', function (Blueprint $table) use ($createdByForeignKey) {
                $table->dropForeign($createdByForeignKey['name']);
            });
        }

        if (Schema::getColumnType('dokumen_perencanaans', 'created_by') !== 'char') {
            Schema::table('dokumen_perencanaans', function (Blueprint $table) {
                $table->uuid('created_by')->nullable()->change();
            });
        }

        Schema::table('dokumen_perencanaans', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('dokumen_perencanaans') || !Schema::hasColumn('dokumen_perencanaans', 'created_by')) {
            return;
        }

        $createdByForeignKey = collect(Schema::getForeignKeys('dokumen_perencanaans'))
            ->first(fn (array $foreignKey) => in_array('created_by', $foreignKey['columns'], true));
        if ($createdByForeignKey) {
            Schema::table('dokumen_perencanaans', function (Blueprint $table) use ($createdByForeignKey) {
                $table->dropForeign($createdByForeignKey['name']);
            });
        }
        Schema::table('dokumen_perencanaans', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->change();
        });
    }
};
