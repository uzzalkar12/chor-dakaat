<?php

namespace App\Events;

use App\Models\GameRoom;
use App\Models\GameRound;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GameRoom $room, public GameRound $round) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('game-room.' . $this->room->room_code)];
    }

    public function broadcastAs(): string
    {
        return 'round.started';
    }

    public function broadcastWith(): array
    {
        $roundTypeNames = ['chor' => 'চোর', 'daakat' => 'ডাকাত'];

        return [
            'round' => [
                'id'            => $this->round->id,
                'round_number'  => $this->round->round_number,
                'round_type'    => $this->round->round_type,
                'round_type_bn' => $roundTypeNames[$this->round->round_type],
                'status'        => $this->round->status,
                'total_rounds'  => $this->room->total_rounds,
                // Each player's assignment - but we send PER USER assignment via channel
                // Client-side: only show your own role, reveal on phase change
                'assignments' => $this->round->assignments->map(fn($a) => [
                    'user_id' => $a->user_id,
                    'role'    => $a->role,
                    'role_bn' => $a->getBengaliRoleName(),
                    'points'  => $a->getBasePoints(),
                ]),
            ],
            'room_status' => [
                'current_round' => $this->room->current_round,
                'players'       => $this->room->players->map(fn($p) => [
                    'user_id'     => $p->user_id,
                    'name'        => $p->user->name,
                    'total_score' => $p->total_score,
                ]),
            ],
        ];
    }
}
