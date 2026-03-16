<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameRound extends Model
{
    protected $fillable = [
        'game_room_id', 'round_number', 'round_type',
        'status', 'police_guess_user_id', 'police_correct'
    ];

    protected $casts = ['police_correct' => 'boolean'];

    public function gameRoom(): BelongsTo
    {
        return $this->belongsTo(GameRoom::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(RoundPlayerAssignment::class);
    }

    public function policeGuessUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'police_guess_user_id');
    }

    public function getPoliceAssignment()
    {
        return $this->assignments()->where('role', 'police')->first();
    }

    public function getTargetRole(): string
    {
        return $this->round_type; // 'chor' or 'daakat'
    }
}
