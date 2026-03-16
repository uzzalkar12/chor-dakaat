<?php

namespace App\Events;

use App\Models\GameRoom;
use App\Models\GameRound;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GameRoom $room, public GameRound $round) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('game-room.' . $this->room->room_code)];
    }

    public function broadcastAs(): string
    {
        return 'round.completed';
    }

    public function broadcastWith(): array
    {
        $this->room->load('players.user');

        return [
            'round' => [
                'id'             => $this->round->id,
                'round_number'   => $this->round->round_number,
                'round_type'     => $this->round->round_type,
                'police_correct' => $this->round->police_correct,
                'guessed_user_id'=> $this->round->police_guess_user_id,
                'assignments'    => $this->round->assignments->map(fn($a) => [
                    'user_id'       => $a->user_id,
                    'role'          => $a->role,
                    'role_bn'       => $a->getBengaliRoleName(),
                    'points_earned' => $a->points_earned,
                ]),
            ],
            'scores' => $this->room->players->map(fn($p) => [
                'user_id'     => $p->user_id,
                'name'        => $p->user->name,
                'total_score' => $p->total_score,
            ]),
        ];
    }
}
