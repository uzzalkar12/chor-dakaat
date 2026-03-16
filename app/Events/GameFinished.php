<?php

namespace App\Events;

use App\Models\GameRoom;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class GameFinished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GameRoom $room, public Collection $players) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('game-room.' . $this->room->room_code)];
    }

    public function broadcastAs(): string
    {
        return 'game.finished';
    }

    public function broadcastWith(): array
    {
        return [
            'winner'  => $this->players->first() ? [
                'user_id' => $this->players->first()->user_id,
                'name'    => $this->players->first()->user->name,
                'score'   => $this->players->first()->total_score,
            ] : null,
            'results' => $this->players->map(fn($p) => [
                'user_id'     => $p->user_id,
                'name'        => $p->user->name,
                'total_score' => $p->total_score,
            ]),
        ];
    }
}
