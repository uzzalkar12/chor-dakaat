<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\GameRoom;

Broadcast::channel('game-room.{roomCode}', function ($user, $roomCode) {
    $room = GameRoom::where('room_code', $roomCode)
        ->with('players')
        ->first();

    if (!$room) return false;

    $player = $room->players()->where('user_id', $user->id)->first();
    if (!$player) return false;

    return [
        'id'          => $user->id,
        'name'        => $user->name,
        'seat_number' => $player->seat_number,
    ];
});
