<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('worker_presence_schedules', function (Blueprint $table) {
            $table->id();
            $table->time('first_check_in_start')->default('07:00:00');
            $table->time('first_check_in_end')->default('08:00:00');
            $table->time('second_check_in_start')->default('13:00:00');
            $table->time('second_check_in_end')->default('14:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_presence_schedules');
    }
};
