<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoundPlayerAssignment extends Model
{
    protected $fillable = ['game_round_id', 'game_room_id', 'user_id', 'role', 'points_earned'];

    // Role point values
    public const ROLE_POINTS = [
        'chor'   => 40,
        'daakat' => 60,
        'police' => 80,
        'babu'   => 100,
    ];

    // Role Bengali names
    public const ROLE_NAMES = [
        'chor'   => 'চোর',
        'daakat' => 'ডাকাত',
        'police' => 'পুলিশ',
        'babu'   => 'বাবু',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameRound(): BelongsTo
    {
        return $this->belongsTo(GameRound::class);
    }

    public function getBengaliRoleName(): string
    {
        return self::ROLE_NAMES[$this->role] ?? $this->role;
    }

    public function getBasePoints(): int
    {
        return self::ROLE_POINTS[$this->role] ?? 0;
    }
}
