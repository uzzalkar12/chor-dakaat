<?php

namespace App\Http\Controllers;

use App\Models\GameRoom;
use App\Models\GameRound;
use App\Services\GameService;
use App\Events\PlayerLeft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function __construct(private GameService $gameService) {}

    /**
     * Lobby - create or join a game
     */
    public function lobby()
    {
        return view('game.lobby', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Create a new game room
     */
    public function createRoom(Request $request)
    {
        $request->validate([
            'total_rounds' => 'integer|min:2|max:20',
        ]);

        $room = $this->gameService->createRoom(
            Auth::id(),
            $request->input('total_rounds', 6)
        );

        return redirect()->route('game.room', $room->room_code);
    }

    /**
     * Join a room by code
     */
    public function joinRoom(Request $request)
    {
        $request->validate(['room_code' => 'required|string|size:6']);

        $result = $this->gameService->joinRoom(
            strtoupper($request->room_code),
            Auth::id()
        );

        if (!$result['success']) {
            return back()->withErrors(['room_code' => $result['message']]);
        }

        return redirect()->route('game.room', $result['room']->room_code);
    }

    /**
     * Show the game room
     */
    public function room(string $roomCode)
    {
        $room = GameRoom::where('room_code', $roomCode)
            ->with(['players.user', 'host'])
            ->firstOrFail();

        $userId = Auth::id();

        // Check if user is in this room
        $player = $room->players()->where('user_id', $userId)->first();
        if (!$player) {
            return redirect()->route('lobby')->withErrors(['আপনি এই রুমে নেই।']);
        }

        $state = $this->gameService->getRoomState($room, $userId);

        return view('game.room', array_merge($state, [
            'userId' => $userId,
        ]));
    }

    /**
     * API: Start the game
     */
    public function startGame(string $roomCode)
    {
        $room = GameRoom::where('room_code', $roomCode)->firstOrFail();

        if ($room->host_user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'শুধু হোস্ট গেম শুরু করতে পারবে।'], 403);
        }

        $result = $this->gameService->startGame($room);
        return response()->json($result);
    }

    /**
     * API: Police submits a guess
     */
    public function policeGuess(Request $request, string $roomCode)
    {
        $request->validate(['guess_user_id' => 'required|integer|exists:users,id']);

        $room = GameRoom::where('room_code', $roomCode)->firstOrFail();
        $currentRound = GameRound::where('game_room_id', $room->id)
            ->where('round_number', $room->current_round)
            ->firstOrFail();

        $result = $this->gameService->policeGuess(
            $currentRound,
            Auth::id(),
            $request->guess_user_id
        );

        return response()->json($result);
    }

    /**
     * API: Start next round (host only)
     */
    public function nextRound(string $roomCode)
    {
        $room = GameRoom::where('room_code', $roomCode)->firstOrFail();

        if ($room->host_user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'শুধু হোস্ট পরবর্তী রাউন্ড শুরু করতে পারবে।'], 403);
        }

        $result = $this->gameService->startNextRound($room);
        return response()->json($result);
    }

    /**
     * API: Leave room — removes player, broadcasts, returns lobby redirect
     */
    public function leaveRoom(string $roomCode)
    {
        $room   = GameRoom::where('room_code', $roomCode)->with('players.user')->firstOrFail();
        $userId = Auth::id();

        $player = $room->players()->where('user_id', $userId)->first();
        if (!$player) {
            return response()->json(['success' => false, 'message' => 'আপনি এই রুমে নেই।']);
        }

        $userName   = $player->user->name;
        $seatNumber = $player->seat_number;   // remember before deleting
        $player->delete();

        $room->refresh()->load('players.user');

        if ($room->players->count() === 0) {
            $room->delete();
        } elseif ($room->host_user_id === $userId) {
            // Host left — transfer host regardless of game state
            $newHost = $room->players->first();
            if ($newHost) {
                $room->update(['host_user_id' => $newHost->user_id]);
                $room->refresh()->load('players.user');
            }
        }

        if ($room->exists && $room->players->count() > 0) {
            broadcast(new PlayerLeft($room, $userId, $userName, $seatNumber));
        }

        return response()->json(['success' => true, 'redirect' => route('lobby')]);
    }

    /**
     * API: Get room state (for polling / page reload)
     */
    public function roomState(string $roomCode)
    {
        $room = GameRoom::where('room_code', $roomCode)
            ->with(['players.user'])
            ->firstOrFail();

        $userId = Auth::id();
        $state  = $this->gameService->getRoomState($room, $userId);

        // Build all players list for JS (needed for police guess screen on reload)
        $allPlayers = $room->players->map(fn($p) => [
            'user_id'     => $p->user_id,
            'name'        => $p->user->name,
            'seat_number' => $p->seat_number,
            'total_score' => $p->total_score,
        ]);

        // Build assignments with role for current round (needed for police panel on reload)
        $assignmentsForJs = null;
        if ($state['currentRound']) {
            $state['currentRound']->load('assignments.user');
            $assignmentsForJs = $state['currentRound']->assignments->map(fn($a) => [
                'user_id' => $a->user_id,
                'role'    => $a->role,
                'role_bn' => $a->getBengaliRoleName(),
                'points'  => $a->getBasePoints(),
            ]);
        }

        return response()->json([
            'room'            => $room,
            'currentRound'    => $state['currentRound'],
            'myAssignment'    => $state['myAssignment'],
            'myPlayer'        => $state['myPlayer'],
            'isHost'          => $state['isHost'],
            'isPolice'        => $state['isPolice'],
            'allPlayers'      => $allPlayers,
            'assignments'     => $assignmentsForJs,
        ]);
    }

    /**
     * Channel auth for presence channel
     */
    public function channelAuth(Request $request)
    {
        $roomCode = $request->channel_name;
        $room     = GameRoom::where('room_code', $roomCode)->first();
        $user     = Auth::user();

        if (!$room || !$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $player = $room->players()->where('user_id', $user->id)->first();
        if (!$player) {
            return response()->json(['error' => 'Not in room'], 403);
        }

        return response()->json([
            'id'   => $user->id,
            'info' => ['name' => $user->name],
        ]);
    }
}
