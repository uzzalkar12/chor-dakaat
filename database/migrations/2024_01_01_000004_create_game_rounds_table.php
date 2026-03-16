<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_room_id')->constrained()->onDelete('cascade');
            $table->integer('round_number');
            // chor=odd rounds, daakat=even rounds
            $table->enum('round_type', ['chor', 'daakat']);
            // Status: picking, police_guessing, completed
            $table->enum('status', ['picking', 'police_guessing', 'completed'])->default('picking');
            // Which user_id the police guessed
            $table->foreignId('police_guess_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('police_correct')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_rounds');
    }
};
