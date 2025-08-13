<?php

// database/migrations/2025_08_13_033443_create_color_sets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('color_sets')) {
            Schema::create('color_sets', function (Blueprint $table) {
                $table->id();
                $table->string('nombre')->unique();
                $table->string('imagen');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('color_sets');
    }
};
