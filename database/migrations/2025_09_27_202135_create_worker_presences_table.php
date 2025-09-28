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
        Schema::create('worker_presences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('worker_id');
            $table->unsignedBigInteger('worker_presence_schedule_id');
            $table->time('first_check_in')->nullable();
            $table->time('second_check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->boolean('is_come_earlier')->default(0);
            $table->boolean('is_work_longer')->default(0);
            $table->boolean('is_overtime')->default(0);

            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            $table->unsignedBigInteger('worker_presence_schedule_id');
            $table->foreign('worker_presence_schedule_id')->references('id')->on('worker_presence_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_presences');
    }
};
