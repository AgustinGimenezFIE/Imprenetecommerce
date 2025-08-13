<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // si tu PKs son bigIncrements, usa foreignId()
            $table->foreignId('color_set_id')
                  ->nullable()
                  ->constrained('color_sets')
                  ->nullOnDelete(); // si borrás el set, deja null en productos
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['color_set_id']);
            $table->dropColumn('color_set_id');
        });
    }
};
