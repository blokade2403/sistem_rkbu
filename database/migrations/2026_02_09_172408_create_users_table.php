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
        Schema::create("users", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("unit_id")->index();
            $table->uuid("position_id")->nullable()->index();
            $table->string("nip")->unique();
            $table->string("nama");
            $table->string("username")->unique();
            $table->string("email")->nullable()->unique();
            $table->string("password");

            $table
                ->enum("status_user", ["aktif", "nonaktif"])
                ->default("aktif");
            $table
                ->enum("status_edit", ["aktif", "nonaktif"])
                ->default("aktif");
            $table->rememberToken();
            $table->timestamps();

            $table
                ->foreign("unit_id")
                ->references("id")
                ->on("units")
                ->cascadeOnDelete();

            $table
                ->foreign("position_id")
                ->references("id")
                ->on("positions")
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropForeign(["unit_id"]);
            $table->dropForeign(["position_id"]);
        });

        Schema::dropIfExists("users");
    }
};
