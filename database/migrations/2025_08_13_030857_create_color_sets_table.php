<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('color_sets', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('imagen'); // ruta en storage/app/public
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('color_sets');
    }
};
