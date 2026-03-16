<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('round_player_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_round_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // chor=40, daakat=60, police=80, babu=100
            $table->enum('role', ['chor', 'daakat', 'police', 'babu']);
            $table->integer('points_earned')->default(0);
            $table->timestamps();

            $table->unique(['game_round_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('round_player_assignments');
    }
};
