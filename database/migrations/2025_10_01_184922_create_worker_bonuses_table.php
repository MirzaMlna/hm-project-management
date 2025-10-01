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
        Schema::create('worker_bonuses', function (Blueprint $table) {
            $table->id();
            $table->integer('work_earlier')->default(0);   // Bonus datang lebih awal
            $table->integer('work_longer')->default(0);    // Bonus kerja lebih lama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_bonuses');
    }
};
