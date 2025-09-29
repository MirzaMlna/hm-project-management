<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('worker_presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->timestamp('first_check_in')->nullable();
            $table->boolean('is_work_earlier')->default(false);
            $table->timestamp('second_check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->boolean('is_work_longer')->default(false);
            $table->boolean('is_overtime')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_presences');
    }
};
