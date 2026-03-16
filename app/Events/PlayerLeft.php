<?php

namespace App\Events;

use App\Models\GameRoom;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerLeft implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public GameRoom $room,
        public int $leftUserId,
        public string $leftUserName,
        public int $leftSeatNumber = 0
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('game-room.' . $this->room->room_code)];
    }

    public function broadcastAs(): string
    {
        return 'player.left';
    }

    public function broadcastWith(): array
    {
        return [
            'left_user_id'     => $this->leftUserId,
            'left_user_name'   => $this->leftUserName,
            'left_seat_number' => $this->leftSeatNumber,
            'new_host_id'      => $this->room->host_user_id,
            'player_count'     => $this->room->players->count(),
            'players'          => $this->room->players->map(fn($p) => [
                'user_id'     => $p->user_id,
                'name'        => $p->user->name,
                'seat_number' => $p->seat_number,
                'total_score' => $p->total_score,
            ]),
        ];
    }
}
