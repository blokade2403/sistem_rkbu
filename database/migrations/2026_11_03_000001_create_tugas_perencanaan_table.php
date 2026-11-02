<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_perencanaan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_informasi');
            $table->text('informasi_tugas');
            $table->string('tugas_dari', 150);
            $table->dateTime('dateline_tugas');
            $table->date('tanggal_selesai')->nullable();
            $table->string('link_tugas', 2048)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('dateline_tugas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_perencanaan');
    }
};
