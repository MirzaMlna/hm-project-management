<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('worker_presence_schedules', function (Blueprint $table) {
            $table->id();
            $table->time('first_check_in_start')->nullable();
            $table->time('first_check_in_end')->nullable();
            $table->time('second_check_in_start')->nullable();
            $table->time('second_check_in_end')->nullable();
            $table->time('check_out_start')->nullable();
            $table->time('check_out_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presence_schedules');
    }
};
