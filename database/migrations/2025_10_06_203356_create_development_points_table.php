<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_points', function (Blueprint $table) {
            $table->id();
            $table->string('development_point'); // Nama titik pembangunan
            $table->string('photo')->nullable(); // Foto opsional (nama file / path)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_points');
    }
};
