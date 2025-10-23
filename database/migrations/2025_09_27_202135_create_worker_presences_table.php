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
            $table->timestamp('second_check_in')->nullable();
            $table->integer('work_longer_count')->default(0);
            $table->boolean('is_overtime')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_presences');
    }
};
