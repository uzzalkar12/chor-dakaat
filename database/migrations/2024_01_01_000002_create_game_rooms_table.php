<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 8)->unique();
            $table->foreignId('host_user_id')->constrained('users')->onDelete('cascade');
            // Status: waiting, playing, finished
            $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
            $table->integer('current_round')->default(0);
            $table->integer('total_rounds')->default(6);
            // Round type: chor or daakat (alternates)
            $table->enum('current_round_type', ['chor', 'daakat'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_rooms');
    }
};
