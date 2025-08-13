<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('ocultar_precio')->default(false);
            $table->string('talla_foto')->nullable();
            $table->string('colores_foto')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['ocultar_precio', 'talla_foto', 'colores_foto']);
        });
    }
};
