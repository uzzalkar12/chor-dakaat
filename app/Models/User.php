<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'total_score', 'games_played'];

    protected $hidden = ['password', 'remember_token'];

    public function gameRooms(): HasMany
    {
        return $this->hasMany(GameRoom::class, 'host_user_id');
    }

    public function roomPlayers(): HasMany
    {
        return $this->hasMany(GameRoomPlayer::class);
    }

    public function roundAssignments(): HasMany
    {
        return $this->hasMany(RoundPlayerAssignment::class);
    }
}
