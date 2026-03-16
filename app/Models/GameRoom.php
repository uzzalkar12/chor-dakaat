<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameRoom extends Model
{
    protected $fillable = [
        'room_code', 'host_user_id', 'status',
        'current_round', 'total_rounds', 'current_round_type'
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(GameRoomPlayer::class)->orderBy('seat_number');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(GameRound::class)->orderBy('round_number');
    }

    public function currentRound()
    {
        return $this->hasOne(GameRound::class)
            ->where('round_number', $this->current_round)
            ->latest();
    }

    public function isFull(): bool
    {
        return $this->players()->count() >= 4;
    }

    public function getNextRoundType(): string
    {
        // Odd rounds = chor, Even rounds = daakat
        $next = $this->current_round + 1;
        return ($next % 2 !== 0) ? 'chor' : 'daakat';
    }
}
