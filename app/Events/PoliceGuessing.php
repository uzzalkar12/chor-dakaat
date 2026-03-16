<?php

namespace App\Events;

use App\Models\GameRoom;
use App\Models\GameRound;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PoliceGuessing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public GameRoom $room,
        public GameRound $round,
        public int $policeUserId
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('game-room.' . $this->room->room_code)];
    }

    public function broadcastAs(): string
    {
        return 'police.guessing';
    }

    public function broadcastWith(): array
    {
        return [
            'round_id'       => $this->round->id,
            'police_user_id' => $this->policeUserId,
            'round_type'     => $this->round->round_type,
        ];
    }
}
