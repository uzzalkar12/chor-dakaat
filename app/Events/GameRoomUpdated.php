<?php

namespace App\Events;

use App\Models\GameRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameRoomUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GameRoom $room) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('game-room.' . $this->room->room_code)];
    }

    public function broadcastAs(): string
    {
        return 'room.updated';
    }

    public function broadcastWith(): array
    {
        // Always reload fresh from DB — prevents stale scores after queue serialization
        $this->room->load('players.user');

        return [
            'room' => [
                'id'           => $this->room->id,
                'room_code'    => $this->room->room_code,
                'status'       => $this->room->status,
                'player_count' => $this->room->players->count(),
                'players'      => $this->room->players->map(fn($p) => [
                    'id'           => $p->id,
                    'user_id'      => $p->user_id,
                    'name'         => $p->user->name,
                    'seat_number'  => $p->seat_number,
                    'total_score'  => $p->total_score,
                    'is_ready'     => $p->is_ready,
                ]),
            ],
        ];
    }
}
