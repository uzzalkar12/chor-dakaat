<?php

namespace App\Services;

use App\Models\GameRoom;
use App\Models\GameRound;
use App\Models\GameRoomPlayer;
use App\Models\RoundPlayerAssignment;
use App\Events\GameRoomUpdated;
use App\Events\RoundStarted;
use App\Events\PoliceGuessing;
use App\Events\RoundCompleted;
use App\Events\GameFinished;
use Illuminate\Support\Str;

class GameService
{
    /**
     * Player leaves a room
     */
    public function leaveRoom(GameRoom $room, int $userId): array
    {
        // Can't leave a game that is in progress
        if ($room->status === 'playing') {
            return ['success' => false, 'message' => 'গেম চলাকালীন রুম ছাড়া যাবে না।'];
        }

        GameRoomPlayer::where('game_room_id', $room->id)
            ->where('user_id', $userId)
            ->delete();

        $room->refresh()->load('players.user');

        // If host left and room still has players, transfer host to seat 1
        if ($room->host_user_id === $userId && $room->players->count() > 0) {
            $newHost = $room->players->sortBy('seat_number')->first();
            $room->update(['host_user_id' => $newHost->user_id]);
            $room->refresh()->load('players.user');
        }

        // If room is empty, delete it
        if ($room->players->count() === 0) {
            $room->delete();
            return ['success' => true, 'redirect' => route('lobby')];
        }

        broadcast(new GameRoomUpdated($room));

        return ['success' => true, 'redirect' => route('lobby')];
    }

    /**
     * Create a new game room
     */
    public function createRoom(int $hostUserId, int $totalRounds = 6): GameRoom
    {
        $room = GameRoom::create([
            'room_code'    => strtoupper(Str::random(6)),
            'host_user_id' => $hostUserId,
            'status'       => 'waiting',
            'total_rounds' => $totalRounds,
            'current_round' => 0,
        ]);

        // Host joins as seat 1
        GameRoomPlayer::create([
            'game_room_id' => $room->id,
            'user_id'      => $hostUserId,
            'seat_number'  => 1,
        ]);

        return $room;
    }

    /**
     * Player joins a room by code
     */
    public function joinRoom(string $roomCode, int $userId): array
    {
        $room = GameRoom::where('room_code', $roomCode)->firstOrFail();

        // Block only if finished
        if ($room->status === 'finished') {
            return ['success' => false, 'message' => 'গেম শেষ হয়ে গেছে।'];
        }

        // If game is playing, only allow joining if room has < 4 players (someone left)
        if ($room->status === 'playing' && $room->isFull()) {
            return ['success' => false, 'message' => 'রুম পূর্ণ — কেউ বের না হওয়া পর্যন্ত যোগ দেওয়া যাবে না।'];
        }

        // If game is waiting and full
        if ($room->status === 'waiting' && $room->isFull()) {
            return ['success' => false, 'message' => 'রুম পূর্ণ হয়ে গেছে।'];
        }

        // Check already joined
        $existing = GameRoomPlayer::where('game_room_id', $room->id)
            ->where('user_id', $userId)->first();
        if ($existing) {
            return ['success' => true, 'room' => $room];
        }

        // Find next available seat
        $takenSeats = $room->players()->pluck('seat_number')->toArray();
        $seat = null;
        for ($i = 1; $i <= 4; $i++) {
            if (!in_array($i, $takenSeats)) {
                $seat = $i;
                break;
            }
        }

        GameRoomPlayer::create([
            'game_room_id' => $room->id,
            'user_id'      => $userId,
            'seat_number'  => $seat,
        ]);

        $freshRoom = $room->fresh()->load('players.user');
        broadcast(new GameRoomUpdated($freshRoom));

        return ['success' => true, 'room' => $freshRoom];
    }

    /**
     * Start the game (host only, needs 4 players)
     */
    public function startGame(GameRoom $room): array
    {
        if ($room->players()->count() < 4) {
            return ['success' => false, 'message' => '৪ জন খেলোয়াড় দরকার।'];
        }

        if ($room->status !== 'waiting') {
            return ['success' => false, 'message' => 'গেম ইতিমধ্যে শুরু হয়েছে।'];
        }

        $room->update(['status' => 'playing']);

        // Start round 1
        return $this->startNextRound($room);
    }

    /**
     * Start the next round
     */
    public function startNextRound(GameRoom $room): array
    {
        $room->refresh();
        $nextRoundNumber = $room->current_round + 1;

        if ($nextRoundNumber > $room->total_rounds) {
            return $this->finishGame($room);
        }

        // Odd = chor, Even = daakat
        $roundType = ($nextRoundNumber % 2 !== 0) ? 'chor' : 'daakat';

        $round = GameRound::create([
            'game_room_id'  => $room->id,
            'round_number'  => $nextRoundNumber,
            'round_type'    => $roundType,
            'status'        => 'picking',
        ]);

        $room->update([
            'current_round'      => $nextRoundNumber,
            'current_round_type' => $roundType,
        ]);

        // Randomly assign roles to players
        $this->assignRoles($round, $room);

        $round->load('assignments.user');
        $room->refresh()->load('players.user');

        broadcast(new RoundStarted($room, $round));

        return ['success' => true, 'round' => $round];
    }

    /**
     * Randomly assign roles to all 4 players
     * Roles: chor(40), daakat(60), police(80), babu(100)
     */
    private function assignRoles(GameRound $round, GameRoom $room): void
    {
        $roles = ['chor', 'daakat', 'police', 'babu'];
        shuffle($roles);

        $players = $room->players()->get();

        foreach ($players as $index => $player) {
            RoundPlayerAssignment::create([
                'game_round_id' => $round->id,
                'game_room_id'  => $room->id,
                'user_id'       => $player->user_id,
                'role'          => $roles[$index],
                'points_earned' => 0,
            ]);
        }

        // Move to police_guessing phase
        $round->update(['status' => 'police_guessing']);
    }

    /**
     * Police makes a guess
     */
    public function policeGuess(GameRound $round, int $policeUserId, int $guessUserId): array
    {
        if ($round->status !== 'police_guessing') {
            return ['success' => false, 'message' => 'এখন অনুমান করার সময় নয়।'];
        }

        // guess_user_id = 0 means police timed out — force forfeit
        if ($guessUserId === 0) {
            $policeAssignment = $round->assignments()
                ->where('user_id', $policeUserId)
                ->where('role', 'police')
                ->first();
            if (!$policeAssignment) {
                return ['success' => false, 'message' => 'আপনি পুলিশ নন।'];
            }
            // Police gets 0 — treat as wrong guess (guess a non-existent user)
            $round->update([
                'police_guess_user_id' => null,
                'police_correct'       => false,
                'status'               => 'completed',
            ]);
            $this->calculateRoundPoints($round, false, $policeAssignment, $round->round_type);
            $round->load('assignments.user');
            broadcast(new RoundCompleted($round->gameRoom, $round));
            return ['success' => true, 'correct' => false, 'timeout' => true, 'round' => $round];
        }

        // Verify this user is actually police
        $policeAssignment = $round->assignments()
            ->where('user_id', $policeUserId)
            ->where('role', 'police')
            ->first();

        if (!$policeAssignment) {
            return ['success' => false, 'message' => 'আপনি পুলিশ নন।'];
        }

        // Check target role for this round type
        $targetRole = $round->round_type; // 'chor' or 'daakat'

        $targetAssignment = $round->assignments()
            ->where('user_id', $guessUserId)
            ->first();

        $correct = $targetAssignment && $targetAssignment->role === $targetRole;

        $round->update([
            'police_guess_user_id' => $guessUserId,
            'police_correct'       => $correct,
            'status'               => 'completed',
        ]);

        // Calculate and store points
        $this->calculateRoundPoints($round, $correct, $policeAssignment, $targetRole);

        $round->load('assignments.user');

        broadcast(new RoundCompleted($round->gameRoom, $round));

        return ['success' => true, 'correct' => $correct, 'round' => $round];
    }

    /**
     * Calculate and save points for the round
     */
    private function calculateRoundPoints(GameRound $round, bool $policeCorrect, $policeAssignment, string $targetRole): void
    {
        $assignments = $round->assignments()->get();

        foreach ($assignments as $assignment) {
            $points = 0;

            if ($assignment->role === 'babu') {
                // Babu always gets 100
                $points = 100;
            } elseif ($assignment->role === 'police') {
                // Police gets 80 if correct, 0 if wrong
                $points = $policeCorrect ? 80 : 0;
            } elseif ($assignment->role === $targetRole) {
                // Target (chor/daakat) gets 0 if caught, full points if not
                $points = $policeCorrect ? 0 : RoundPlayerAssignment::ROLE_POINTS[$targetRole];
            } else {
                // Other role (daakat in chor round or chor in daakat round) always gets their points
                $points = RoundPlayerAssignment::ROLE_POINTS[$assignment->role];
            }

            $assignment->update(['points_earned' => $points]);

            // Update player's total score in the room
            GameRoomPlayer::where('game_room_id', $round->game_room_id)
                ->where('user_id', $assignment->user_id)
                ->increment('total_score', $points);
        }
    }

    /**
     * Finish the game
     */
    private function finishGame(GameRoom $room): array
    {
        $room->update(['status' => 'finished']);

        $players = $room->players()->with('user')->orderByDesc('total_score')->get();

        // Update user global stats
        foreach ($players as $player) {
            $player->user->increment('total_score', $player->total_score);
            $player->user->increment('games_played');
        }

        broadcast(new GameFinished($room, $players));

        return ['success' => true, 'finished' => true, 'players' => $players];
    }

    /**
     * Get current room state for a player
     */
    public function getRoomState(GameRoom $room, int $userId): array
    {
        $room->load(['players.user', 'rounds.assignments.user', 'host']);
        $currentRound = $room->rounds()->where('round_number', $room->current_round)->first();

        $myAssignment = null;
        if ($currentRound) {
            $currentRound->load('assignments.user');
            $myAssignment = $currentRound->assignments()
                ->where('user_id', $userId)->first();
        }

        return [
            'room'          => $room,
            'currentRound'  => $currentRound,
            'myAssignment'  => $myAssignment,
            'myPlayer'      => $room->players()->where('user_id', $userId)->first(),
            'isHost'        => $room->host_user_id == $userId,
            'isPolice'      => $myAssignment && $myAssignment->role === 'police',
        ];
    }
}
