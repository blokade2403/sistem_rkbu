<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('master_spjs')) {
            return;
        }

        Schema::table('master_spjs', function (Blueprint $table) {
            if (! Schema::hasColumn('master_spjs', 'upload_spj_1')) {
                $table->string('upload_spj_1')->nullable();
            }
            if (! Schema::hasColumn('master_spjs', 'upload_spj_2')) {
                $table->string('upload_spj_2')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('master_spjs')) {
            return;
        }

        Schema::table('master_spjs', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['upload_spj_1', 'upload_spj_2'],
                fn ($column) => Schema::hasColumn('master_spjs', $column)
            ));
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
