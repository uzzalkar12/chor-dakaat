@extends('layouts.app')
@section('title', 'রুম: ' . $room->room_code)

@push('styles')
<style>
/* ===== ROOM LAYOUT ===== */
.room-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
}
@media(max-width: 768px) { .room-layout { grid-template-columns: 1fr; } }

/* ===== SIDEBAR ===== */
.sidebar { display: flex; flex-direction: column; gap: 1rem; }

.room-code-box {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
}
.room-code-label { color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.3rem; }
.room-code-value {
    font-size: 2.2rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    color: var(--accent-gold);
    cursor: pointer;
    font-family: monospace;
}
.room-code-value:hover { opacity: 0.8; }
.copy-hint { color: var(--text-muted); font-size: 0.75rem; margin-top: 0.3rem; }

.players-box {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    flex: 1;
}
.players-box h3 {
    font-size: 0.85rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1rem;
}
.player-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.7rem 0.8rem;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    border: 1px solid transparent;
    transition: all 0.2s;
}
.player-row.is-me { background: rgba(78,168,222,0.08); border-color: rgba(78,168,222,0.2); }

/* Fix 3: online dot appears immediately from .here() — no reload */
.player-row.is-online::before {
    content: '';
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--accent-green);
    box-shadow: 0 0 5px var(--accent-green);
    flex-shrink: 0;
}

.player-seat {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--bg-glass);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; color: var(--text-muted);
    flex-shrink: 0;
}
.player-info { flex: 1; min-width: 0; }
.player-name { font-size: 0.9rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.player-score { font-size: 0.75rem; color: var(--text-muted); }
.player-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    background: rgba(244,165,34,0.15);
    color: var(--accent-gold);
    border: 1px solid rgba(244,165,34,0.3);
}
.empty-seat { opacity: 0.4; border: 1px dashed var(--border) !important; }

/* Leave button */
.btn-leave {
    width: 100%;
    padding: 0.65rem;
    border-radius: 10px;
    border: 1px solid rgba(230,57,70,0.3);
    background: rgba(230,57,70,0.07);
    color: var(--accent-red);
    cursor: pointer;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    margin-top: 1rem;
}
.btn-leave:hover { background: rgba(230,57,70,0.15); border-color: var(--accent-red); }

/* ===== MAIN GAME AREA ===== */
.game-main { display: flex; flex-direction: column; gap: 1rem; }

.round-header {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.round-info { display: flex; align-items: center; gap: 1rem; }
.round-badge { padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; }
.round-badge.chor   { background: rgba(230,57,70,0.15); color: var(--accent-red); border: 1px solid rgba(230,57,70,0.3); }
.round-badge.daakat { background: rgba(199,125,255,0.15); color: #c77dff; border: 1px solid rgba(199,125,255,0.3); }
.round-progress { display: flex; gap: 0.3rem; align-items: center; }
.progress-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--border); transition: background 0.3s; }
.progress-dot.done-chor   { background: var(--accent-red); }
.progress-dot.done-daakat { background: #c77dff; }
.progress-dot.current     { background: var(--accent-gold); box-shadow: 0 0 8px var(--accent-gold); }

/* ===== WAITING SCREEN ===== */
.waiting-screen {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    flex: 1;
}
.waiting-screen h2 { font-family: 'Tiro Bangla', serif; font-size: 1.6rem; margin-bottom: 0.5rem; }
.waiting-screen p  { color: var(--text-muted); margin-bottom: 2rem; }
.waiting-dots span {
    display: inline-block;
    width: 10px; height: 10px;
    border-radius: 50%;
    background: var(--accent-blue);
    margin: 0 3px;
    animation: bounce 1.4s infinite ease-in-out;
}
.waiting-dots span:nth-child(2) { animation-delay: 0.2s; }
.waiting-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes bounce {
    0%,80%,100% { transform: scale(0); }
    40% { transform: scale(1); }
}
.player-slots { display: flex; justify-content: center; gap: 1rem; margin: 2rem 0; flex-wrap: wrap; }
.slot-card {
    width: 90px;
    background: var(--bg-glass);
    border: 1px dashed var(--border);
    border-radius: 14px;
    padding: 1rem 0.5rem;
    text-align: center;
    transition: all 0.3s;
}
.slot-card.filled { border-style: solid; border-color: var(--accent-green); background: rgba(87,204,153,0.05); }
.slot-card .s-emoji { font-size: 1.8rem; }
.slot-card .s-name  { font-size: 0.75rem; margin-top: 0.3rem; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ===== MY ROLE CARD ===== */
.my-role-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
}
.my-role-card {
    display: inline-block;
    padding: 1.5rem 3rem;
    border-radius: 16px;
    margin: 1rem auto;
    min-width: 200px;
}
.my-role-card.chor   { background: rgba(230,57,70,0.1); border: 2px solid rgba(230,57,70,0.4); }
.my-role-card.daakat { background: rgba(199,125,255,0.1); border: 2px solid rgba(199,125,255,0.4); }
.my-role-card.police { background: rgba(78,168,222,0.1); border: 2px solid rgba(78,168,222,0.4); }
.my-role-card.babu   { background: rgba(244,165,34,0.1); border: 2px solid rgba(244,165,34,0.4); }
.role-big-emoji { font-size: 3.5rem; display: block; margin-bottom: 0.5rem; }
.role-big-name  { font-size: 1.8rem; font-weight: 700; font-family: 'Tiro Bangla', serif; }
.role-big-pts   { font-size: 1rem; color: var(--text-muted); margin-top: 0.3rem; }
.role-desc {
    margin-top: 1rem; padding: 1rem;
    background: var(--bg-glass); border-radius: 10px;
    color: var(--text-muted); font-size: 0.9rem;
    max-width: 400px; margin-left: auto; margin-right: auto;
}

/* ===== POLICE GUESSING ===== */
.police-section {
    background: var(--bg-card);
    border: 1px solid rgba(78,168,222,0.3);
    border-radius: 20px;
    padding: 2rem;
}
.police-section h3 { color: var(--accent-blue); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
.guess-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin-top: 1rem;
}
@media(max-width: 500px) { .guess-grid { grid-template-columns: 1fr 1fr; } }

/* Fix 1: guess button base */
.guess-btn {
    position: relative;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-glass);
    color: var(--text-main);
    cursor: pointer;
    font-family: inherit;
    font-size: 0.95rem;
    transition: all 0.2s;
    text-align: center;
    overflow: hidden;
    min-height: 70px;
}
.guess-btn:not(:disabled):not(.babu-btn):hover {
    border-color: var(--accent-blue);
    background: rgba(78,168,222,0.1);
    transform: translateY(-2px);
}
.guess-btn .g-name  { font-weight: 600; display: block; }
.guess-btn .g-hint  { font-size: 0.72rem; margin-top: 0.25rem; display: block; }

/* Fix 1: babu button — visually distinct, cannot click */
.guess-btn.babu-btn {
    border-color: rgba(244,165,34,0.4) !important;
    background: rgba(244,165,34,0.07) !important;
    cursor: not-allowed;
    opacity: 0.75;
}
.guess-btn.babu-btn .g-name { color: var(--accent-gold); }
.guess-btn.babu-btn .g-hint { color: rgba(244,165,34,0.7); font-style: italic; }

/* Fix 1: loading state on guess button */
.guess-btn.loading .g-name,
.guess-btn.loading .g-hint { visibility: hidden; }
.guess-btn.loading::before {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 22px; height: 22px;
    border: 3px solid rgba(78,168,222,0.25);
    border-top-color: var(--accent-blue);
    border-radius: 50%;
    animation: spinBtn 0.65s linear infinite;
}
@keyframes spinBtn { to { transform: translate(-50%,-50%) rotate(360deg); } }

.waiting-police { text-align: center; padding: 1.5rem; color: var(--text-muted); }
.pulse-icon { font-size: 2.5rem; animation: pulse 1.5s infinite; }
@keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }

/* ===== ROUND RESULT ===== */
.round-result { background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; padding: 2rem; }
.result-banner {
    text-align: center; padding: 1.2rem; border-radius: 14px;
    margin-bottom: 1.5rem; font-size: 1.2rem; font-weight: 700;
}
.result-banner.correct { background: rgba(87,204,153,0.15); color: var(--accent-green); border: 1px solid rgba(87,204,153,0.3); }
.result-banner.wrong   { background: rgba(230,57,70,0.15); color: var(--accent-red); border: 1px solid rgba(230,57,70,0.3); }
.results-table { width: 100%; border-collapse: collapse; }
.results-table th { text-align: left; padding: 0.6rem 0.8rem; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
.results-table td { padding: 0.8rem; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.95rem; }
.results-table tr:last-child td { border-bottom: none; }
.pts-earned { font-weight: 700; font-size: 1.1rem; }
.pts-zero    { color: var(--accent-red); }
.pts-positive{ color: var(--accent-green); }

/* ===== GAME OVER ===== */
.game-over { background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; padding: 3rem 2rem; text-align: center; }
.game-over h2 {
    font-family: 'Tiro Bangla', serif; font-size: 2rem; margin-bottom: 0.5rem;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-red));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.final-scores { max-width: 400px; margin: 1.5rem auto; }
.final-score-row { display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1rem; border-radius: 10px; margin-bottom: 0.5rem; }
.final-score-row.first-place { background: rgba(244,165,34,0.1); border: 1px solid rgba(244,165,34,0.3); }
.rank-badge { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
.rank-1 { background: var(--accent-gold); color: #1a1000; }
.rank-2 { background: #adb5bd; color: #1a1a1a; }
.rank-3 { background: #cd7f32; color: #fff; }
.rank-4 { background: var(--bg-glass); color: var(--text-muted); border: 1px solid var(--border); }

/* ===== LEAVE CONFIRM MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.72);
    z-index: 500;
    align-items: center;
    justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    max-width: 360px; width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    animation: popIn 0.18s ease;
}
@keyframes popIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-box h3 { font-size: 1.3rem; margin-bottom: 0.5rem; }
.modal-box p  { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.6; }
.modal-btns   { display: flex; gap: 0.75rem; justify-content: center; }
</style>
@endpush

@section('content')
<div class="room-layout">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="room-code-box">
            <div class="room-code-label">রুম কোড</div>
            <div class="room-code-value" onclick="copyCode('{{ $room->room_code }}')">
                {{ $room->room_code }}
            </div>
            <div class="copy-hint">👆 ক্লিক করে কপি করুন</div>
        </div>

        <div class="players-box">
            <h3 id="playerCountLabel">খেলোয়াড়গণ ({{ $room->players->count() }}/4)</h3>
            <div id="playersList">
                @for($i = 1; $i <= 4; $i++)
                    @php $p = $room->players->firstWhere('seat_number', $i); @endphp
                    @if($p)
                        <div class="player-row {{ $p->user_id == $userId ? 'is-me' : '' }}"
                             data-user-id="{{ $p->user_id }}"
                             id="playerRow-{{ $p->user_id }}">
                            <div class="player-seat">{{ $i }}</div>
                            <div class="player-info">
                                <div class="player-name">{{ $p->user->name }}</div>
                                <div class="player-score" id="score-{{ $p->user_id }}">{{ $p->total_score }} পয়েন্ট</div>
                            </div>
                            @if($room->host_user_id == $p->user_id)
                                <div class="player-badge" id="hostBadge-{{ $p->user_id }}">হোস্ট</div>
                            @endif
                            @if($p->user_id == $userId)
                                <div class="player-badge" style="background:rgba(78,168,222,0.15);color:var(--accent-blue);border-color:rgba(78,168,222,0.3);">আপনি</div>
                            @endif
                        </div>
                    @else
                        <div class="player-row empty-seat" id="slot-{{ $i }}">
                            <div class="player-seat">{{ $i }}</div>
                            <div class="player-info">
                                <div class="player-name" style="color:var(--text-muted)">অপেক্ষায়...</div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>

            {{-- Fix 2: Leave room button — only while waiting --}}
            <button class="btn-leave" onclick="confirmLeave()">🚪 রুম ছেড়ে যান</button>
        </div>
    </div>

    <!-- MAIN GAME -->
    <div class="game-main" id="gameMain">
        <div class="round-header" id="roundHeader"
             style="{{ $room->status == 'waiting' ? 'display:none' : '' }}">
            <div class="round-info">
                <span id="roundLabel" class="round-badge chor">রাউন্ড ১ — চোর রাউন্ড</span>
            </div>
            <div class="round-progress" id="roundProgress">
                @for($i = 1; $i <= $room->total_rounds; $i++)
                    <div class="progress-dot {{ $i < $room->current_round ? 'done-chor' : ($i == $room->current_round ? 'current' : '') }}"
                         id="dot-{{ $i }}" title="রাউন্ড {{ $i }}"></div>
                @endfor
            </div>
        </div>

        <div id="contentArea">
            @if($room->status == 'waiting')
                @include('game.partials.waiting')
            @elseif($room->status == 'playing')
                @include('game.partials.playing')
            @elseif($room->status == 'finished')
                @include('game.partials.finished')
            @endif
        </div>
    </div>
</div>

<!-- Fix 2: Leave confirm modal -->
<div class="modal-overlay" id="leaveModal">
    <div class="modal-box">
        <h3>🚪 রুম ছেড়ে যাবেন?</h3>
        <p>আপনি রুম ছেড়ে গেলে লবিতে ফিরে যাবেন। অন্য খেলোয়াড়রা অপেক্ষা করতে থাকবেন।</p>
        <div class="modal-btns">
            <button class="btn btn-ghost" onclick="closeLeaveModal()">বাতিল</button>
            <button class="btn btn-primary" id="confirmLeaveBtn" onclick="doLeave()">হ্যাঁ, চলে যান</button>
        </div>
    </div>
</div>

<!-- PHP → JS -->
<script>
    const ROOM_CODE    = '{{ $room->room_code }}';
    const ROOM_ID      = {{ $room->id }};
    const USER_ID      = {{ $userId }};
    const IS_HOST      = {{ $isHost ? 'true' : 'false' }};
    const TOTAL_ROUNDS = {{ $room->total_rounds }};
    const ROOM_STATUS  = '{{ $room->status }}';

    @if($myAssignment)
    let MY_ROLE = '{{ $myAssignment->role }}';
    @else
    let MY_ROLE = null;
    @endif

    const ROLE_EMOJI = { chor:'🦹', daakat:'💀', police:'👮', babu:'🎩' };
    const ROLE_BN    = { chor:'চোর', daakat:'ডাকাত', police:'পুলিশ', babu:'বাবু' };
    const ROLE_PTS   = { chor:40, daakat:60, police:80, babu:100 };

    // Fix 3: track online users from presence channel immediately
    let onlineUserIds = new Set();
</script>
@endsection

@push('scripts')
{{-- Pusher FIRST, then Echo --}}
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
// ===== REVERB SETUP =====
// Reverb uses the Pusher protocol — broadcaster must be 'pusher'
// The IIFE build of laravel-echo does NOT ship a 'reverb' connector;
// use 'pusher' broadcaster pointed at the Reverb WS server instead.
const echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ config("reverb.apps.apps.0.key", env("REVERB_APP_KEY", "app-key")) }}',
    wsHost: '{{ env("REVERB_HOST", "127.0.0.1") }}',
    wsPort: {{ env("REVERB_PORT", 8080) }},
    wssPort: {{ env("REVERB_PORT", 8080) }},
    cluster: 'mt1',           // required by Pusher.js but ignored by Reverb
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws'],
    authEndpoint: '/broadcasting/auth',
});

// ===== PRESENCE CHANNEL =====
// Fix 3: .here() fires immediately after WS handshake (no page reload needed)
const channel = echo.join(`game-room.${ROOM_CODE}`)
    .here((users) => {
        // Populate online set at connection time — instant, no refresh
        onlineUserIds = new Set(users.map(u => u.id));
        users.forEach(u => setOnlineDot(u.id, true));
    })
    .joining((user) => {
        if (onlineUserIds.has(user.id)) return; // already counted
        onlineUserIds.add(user.id);
        setOnlineDot(user.id, true);
        showToast(`🟢 ${user.name} অনলাইনে এলেন`, 'success', 2500);
    })
    .leaving((user) => {
        onlineUserIds.delete(user.id);
        setOnlineDot(user.id, false);
        showToast(`🔴 ${user.name} অফলাইন হলেন`, 'info', 2500);
    });

// ===== EVENTS =====

channel.listen('.room.updated', (data) => {
    const players = data.room.players;
    const count   = data.room.player_count;

    // 1. Sidebar count label
    document.getElementById('playerCountLabel').textContent =
        `খেলোয়াড়গণ (${count}/4)`;

    // 2. Sidebar rows — update existing scores OR insert new player rows
    players.forEach(p => {
        const existingRow = document.getElementById(`playerRow-${p.user_id}`);
        if (existingRow) {
            // Existing player — update score ONLY if incoming value is >= current
            // (prevents a stale broadcast from wiping earned scores)
            const scoreEl = document.getElementById(`score-${p.user_id}`);
            if (scoreEl) {
                const currentScore = parseInt(scoreEl.textContent) || 0;
                if (p.total_score >= currentScore) {
                    scoreEl.textContent = `${p.total_score} পয়েন্ট`;
                }
            }
        } else {
            // Brand-new player joining — only THEIR slot gets replaced, others untouched
            const isMe = (p.user_id == USER_ID);
            const row  = document.createElement('div');
            row.className      = `player-row${isMe ? ' is-me' : ''}`;
            row.id             = `playerRow-${p.user_id}`;
            row.dataset.userId = p.user_id;
            row.innerHTML = `
                <div class="player-seat">${p.seat_number}</div>
                <div class="player-info">
                    <div class="player-name">${p.name}</div>
                    <div class="player-score" id="score-${p.user_id}">০ পয়েন্ট</div>
                </div>
                ${isMe ? '<div class="player-badge" style="background:rgba(78,168,222,0.15);color:var(--accent-blue);border-color:rgba(78,168,222,0.3);">আপনি</div>' : ''}
            `;
            // Replace only the empty slot for THIS seat — never touch other players' rows
            const emptySlot = document.getElementById(`slot-${p.seat_number}`);
            if (emptySlot) {
                emptySlot.replaceWith(row);
            } else {
                // Slot may not exist (e.g. re-joining a playing game) — append at end
                document.getElementById('playersList').appendChild(row);
            }

            // Toast for other users only
            if (!isMe) showToast(`👋 ${p.name} রুমে যোগ দিয়েছেন!`, 'success', 3500);
        }
    });

    // 3. Waiting screen slots (big card view)
    if (document.getElementById('waitSlots')) {
        updateWaitingSlots(players, count);
    }

    // 4. Start button / hint (host only)
    const startBtn = document.getElementById('startBtn');
    if (startBtn) {
        if (count >= 4) {
            startBtn.removeAttribute('disabled');
            const hint = document.getElementById('waitHint');
            if (hint) hint.style.display = 'none';
        } else {
            startBtn.setAttribute('disabled', true);
            const hint = document.getElementById('waitHint');
            if (hint) { hint.style.display = ''; hint.textContent = `আরও ${4 - count} জন লাগবে`; }
        }
    }
});

// ── player.left ──────────────────────────────────────────────────────────────
channel.listen('.player.left', (data) => {
    const leftSeat = data.left_seat_number;  // sent from backend (see PlayerLeft event)

    // 1. Toast notify remaining users
    showToast(`🚪 ${data.left_user_name} রুম ছেড়ে গেছেন`, 'error', 4000);

    // 2. Remove the leaver's sidebar row
    document.getElementById(`playerRow-${data.left_user_id}`)?.remove();

    // 3. Put back an empty-seat placeholder in the sidebar at that seat
    if (leftSeat) {
        const list = document.getElementById('playersList');
        // Find insertion point: after the previous seat row (or prepend)
        const emptyRow = document.createElement('div');
        emptyRow.className = 'player-row empty-seat';
        emptyRow.id        = `slot-${leftSeat}`;
        emptyRow.innerHTML = `
            <div class="player-seat">${leftSeat}</div>
            <div class="player-info">
                <div class="player-name" style="color:var(--text-muted)">অপেক্ষায়...</div>
            </div>`;
        // Insert in seat order
        let inserted = false;
        const rows = list.querySelectorAll('.player-row');
        for (const row of rows) {
            const rowSeat = parseInt(row.querySelector('.player-seat')?.textContent || '99');
            if (rowSeat > leftSeat) {
                list.insertBefore(emptyRow, row);
                inserted = true;
                break;
            }
        }
        if (!inserted) list.appendChild(emptyRow);
    }

    // 4. Update host badge if host changed
    document.querySelectorAll('[id^="hostBadge-"]').forEach(b => b.remove());
    const newHostRow = document.getElementById(`playerRow-${data.new_host_id}`);
    if (newHostRow) {
        const badge = document.createElement('div');
        badge.className = 'player-badge';
        badge.id        = `hostBadge-${data.new_host_id}`;
        badge.textContent = 'হোস্ট';
        newHostRow.appendChild(badge);
    }

    // 5. Update count label
    document.getElementById('playerCountLabel').textContent =
        `খেলোয়াড়গণ (${data.player_count}/4)`;

    // 6. Waiting screen big slots + start button
    if (document.getElementById('waitSlots')) {
        updateWaitingSlots(data.players, data.player_count);
    }
    const startBtn = document.getElementById('startBtn');
    if (startBtn && data.player_count < 4) {
        startBtn.setAttribute('disabled', true);
        const hint = document.getElementById('waitHint');
        if (hint) { hint.style.display = ''; hint.textContent = `আরও ${4 - data.player_count} জন লাগবে`; }
    }
});

channel.listen('.round.started', (data) => {
    const round = data.round;
    MY_ROLE = round.assignments.find(a => a.user_id == USER_ID)?.role || null;
    document.getElementById('roundHeader').style.display = 'flex';
    updateRoundHeader(round.round_number, round.round_type, round.round_type_bn);
    updateProgressDots(round.round_number);
    renderMyRolePhase(round, data.room_status);
});

channel.listen('.round.completed', (data) => {
    renderRoundResult(data.round, data.scores);
});

channel.listen('.game.finished', (data) => {
    renderGameFinished(data);
});

// ===== HELPERS =====

function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => showToast('✅ কোড কপি হয়েছে!', 'success'));
}

// Fix 3: set/unset online dot without page reload
function setOnlineDot(userId, online) {
    const row = document.getElementById(`playerRow-${userId}`);
    if (!row) return;
    row.classList.toggle('is-online', online);
}

function updateRoundHeader(num, type, typeBn) {
    const label = document.getElementById('roundLabel');
    label.textContent = `রাউন্ড ${num}/${TOTAL_ROUNDS} — ${typeBn} রাউন্ড`;
    label.className = `round-badge ${type}`;
}

function updateProgressDots(current) {
    for (let i = 1; i <= TOTAL_ROUNDS; i++) {
        const dot = document.getElementById(`dot-${i}`);
        if (!dot) continue;
        dot.className = 'progress-dot';
        if (i < current)  dot.classList.add(i % 2 !== 0 ? 'done-chor' : 'done-daakat');
        if (i === current) dot.classList.add('current');
    }
}

function updateWaitingSlots(players, count) {
    // Reset all 4 slots first, then fill from current player list
    for (let seat = 1; seat <= 4; seat++) {
        const slot = document.getElementById(`wslot-${seat}`);
        if (!slot) continue;
        const player = players.find(p => p.seat_number == seat);
        if (player) {
            slot.classList.add('filled');
            slot.querySelector('.s-emoji').textContent = '👤';
            slot.querySelector('.s-name').textContent  = player.name;
        } else {
            slot.classList.remove('filled');
            slot.querySelector('.s-emoji').textContent = '❓';
            slot.querySelector('.s-name').textContent  = 'খালি';
        }
    }
    const el = document.getElementById('waitCount');
    if (el) el.textContent = `${count ?? players.length}/4 জন যোগ দিয়েছেন`;
}

// Fix 1: build police guess grid
// - babu button is shown but disabled + gold-labelled
// - police cannot click বাবু
function buildGuessGrid(assignments, roomPlayers, roundTypeBn) {
    const others = assignments.filter(a => a.user_id != USER_ID);
    return others.map(a => {
        const name = roomPlayers.find(p => p.user_id == a.user_id)?.name || '?';
        if (a.role === 'babu') {
            return `<button class="guess-btn babu-btn" disabled title="বাবু ধরা যায় না">
                        <span class="g-name">🎩 ${name}</span>
                        <span class="g-hint">বাবু — ধরা যাবে না</span>
                    </button>`;
        }
        return `<button class="guess-btn" data-uid="${a.user_id}"
                        onclick="submitGuess(${a.user_id}, this)">
                    <span class="g-name">👤 ${name}</span>
                    <span class="g-hint">সন্দেহজনক?</span>
                </button>`;
    }).join('');
}

function renderMyRolePhase(round, roomStatus) {
    const role      = MY_ROLE;
    const emoji     = ROLE_EMOJI[role];
    const nameBn    = ROLE_BN[role];
    const pts       = ROLE_PTS[role];
    const typeBn    = round.round_type_bn;

    const descMap = {
        police: `আপনি পুলিশ! ${typeBn}কে খুঁজে বের করুন। সঠিক হলে ৮০ পয়েন্ট পাবেন।`,
        chor:   `আপনি চোর! পুলিশ থেকে লুকিয়ে থাকুন। ধরা না পড়লে ৪০ পয়েন্ট পাবেন।`,
        daakat: `আপনি ডাকাত! পুলিশ থেকে লুকিয়ে থাকুন। ধরা না পড়লে ৬০ পয়েন্ট পাবেন।`,
        babu:   `আপনি বাবু! নিশ্চিন্তে বসে থাকুন — আপনি সর্বদা ১০০ পয়েন্ট পাবেন।`,
    };

    let bottomSection = '';
    if (role === 'police') {
        const grid = buildGuessGrid(round.assignments, roomStatus.players, typeBn);
        bottomSection = `
            <div class="police-section" style="margin-top:1rem">
                <h3>👮 ${typeBn}কে চিহ্নিত করুন</h3>
                <p style="color:var(--text-muted);font-size:0.9rem">
                    কে ${typeBn}? বাবু ছাড়া যেকোনো খেলোয়াড়কে সিলেক্ট করুন:
                </p>
                <div class="guess-grid">${grid}</div>
            </div>`;
    } else {
        bottomSection = `
            <div class="police-section" style="margin-top:1rem;border-color:var(--border)">
                <div class="waiting-police">
                    <div class="pulse-icon">👮</div>
                    <p style="margin-top:0.8rem;font-size:1rem">পুলিশ সিদ্ধান্ত নিচ্ছেন...</p>
                    <p style="color:var(--text-muted);font-size:0.85rem;margin-top:0.3rem">অপেক্ষা করুন</p>
                </div>
            </div>`;
    }

    document.getElementById('contentArea').innerHTML = `
        <div class="my-role-section">
            <div style="color:var(--text-muted);font-size:0.85rem;margin-bottom:0.5rem">এই রাউন্ডে আপনার রোল</div>
            <div class="my-role-card ${role}">
                <span class="role-big-emoji">${emoji}</span>
                <div class="role-big-name role-${role}">${nameBn}</div>
                <div class="role-big-pts">${pts} পয়েন্ট</div>
            </div>
            <div class="role-desc">${descMap[role]}</div>
        </div>
        ${bottomSection}`;

    // Reapply online dots after DOM rebuild
    onlineUserIds.forEach(id => setOnlineDot(id, true));
}

function renderRoundResult(round, scores) {
    const targetBn = round.round_type === 'chor' ? 'চোর' : 'ডাকাত';
    const banner   = round.police_correct
        ? `✅ পুলিশ ${targetBn}কে ধরে ফেলেছে!`
        : `❌ পুলিশ ভুল করেছে! ${targetBn} পালিয়ে গেছে!`;

    const rows = round.assignments.map(a => {
        const cls  = a.points_earned === 0 ? 'pts-zero' : 'pts-positive';
        const me   = a.user_id == USER_ID ? '<small>(আপনি)</small>' : '';
        const name = scores.find(s => s.user_id == a.user_id)?.name || '?';
        return `<tr>
            <td>${name} ${me}</td>
            <td class="role-${a.role}">${a.role_bn}</td>
            <td class="pts-earned ${cls}">${a.points_earned > 0 ? '+' : ''}${a.points_earned}</td>
        </tr>`;
    }).join('');

    scores.forEach(s => {
        const el = document.getElementById(`score-${s.user_id}`);
        if (el) el.textContent = `${s.total_score} পয়েন্ট`;
    });

    const nextBtn = IS_HOST
        ? `<button class="btn btn-gold" onclick="startNextRound(this)" style="margin-top:1.5rem">▶️ পরবর্তী রাউন্ড</button>`
        : `<p style="color:var(--text-muted);margin-top:1.5rem;font-size:0.9rem">হোস্ট পরবর্তী রাউন্ড শুরু করার অপেক্ষায়...</p>`;

    document.getElementById('contentArea').innerHTML = `
        <div class="round-result">
            <div class="result-banner ${round.police_correct ? 'correct' : 'wrong'}">${banner}</div>
            <table class="results-table">
                <thead><tr><th>খেলোয়াড়</th><th>রোল</th><th>পয়েন্ট</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>
            <div style="text-align:center">${nextBtn}</div>
        </div>`;
}

function renderGameFinished(data) {
    document.getElementById('roundHeader').style.display = 'none';

    const rows = data.results.map((r, i) => {
        const rank = i + 1;
        const me   = r.user_id == USER_ID ? ' (আপনি)' : '';
        return `<div class="final-score-row ${rank===1?'first-place':''}">
            <div class="rank-badge rank-${rank}">${rank}</div>
            <div style="flex:1;font-weight:${rank===1?700:400}">${r.name}${me}</div>
            <div style="font-size:1.2rem;font-weight:700;color:${rank===1?'var(--accent-gold)':'var(--text-main)'}">${r.total_score}</div>
        </div>`;
    }).join('');

    document.getElementById('contentArea').innerHTML = `
        <div class="game-over">
            <div style="font-size:3rem">🏆</div>
            <h2>গেম শেষ!</h2>
            <p style="color:var(--text-muted)">বিজয়ী: <strong style="color:var(--accent-gold)">${data.winner?.name}</strong></p>
            <div class="final-scores">${rows}</div>
            <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:center">
                <a href="{{ route('lobby') }}" class="btn btn-secondary">🏠 লবিতে ফিরুন</a>
            </div>
        </div>`;
}

// ===== ACTIONS =====

async function startGame() {
    const btn = document.getElementById('startBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'শুরু হচ্ছে...'; }
    const data = await apiPost(`/api/room/${ROOM_CODE}/start`);
    if (!data.success) {
        showToast(data.message || 'ত্রুটি হয়েছে', 'error');
        if (btn) { btn.disabled = false; btn.textContent = '🎮 গেম শুরু করুন'; }
    }
}

// Fix 1: loader on clicked button; babu buttons already disabled via HTML
async function submitGuess(guessUserId, btn) {
    btn.classList.add('loading');
    // Disable all clickable buttons (not babu — already disabled)
    document.querySelectorAll('.guess-btn:not(.babu-btn)').forEach(b => b.disabled = true);

    const data = await apiPost(`/api/room/${ROOM_CODE}/guess`, { guess_user_id: guessUserId });
    if (!data.success) {
        showToast(data.message || 'ত্রুটি হয়েছে', 'error');
        btn.classList.remove('loading');
        document.querySelectorAll('.guess-btn:not(.babu-btn)').forEach(b => b.disabled = false);
    }
    // On success the round.completed event will replace contentArea automatically
}

async function startNextRound(btn) {
    // btn may be undefined if called without `this` — guard against it
    if (btn) { btn.disabled = true; btn.textContent = 'শুরু হচ্ছে...'; }
    const data = await apiPost(`/api/room/${ROOM_CODE}/next-round`);
    if (!data.success) {
        showToast(data.message || 'ত্রুটি হয়েছে', 'error');
        if (btn) { btn.disabled = false; btn.textContent = '▶️ পরবর্তী রাউন্ড'; }
    }
}

// Fix 2: leave room
function confirmLeave() {
    document.getElementById('leaveModal').classList.add('active');
}
function closeLeaveModal() {
    document.getElementById('leaveModal').classList.remove('active');
}
async function doLeave() {
    const btn = document.getElementById('confirmLeaveBtn');
    btn.disabled = true;
    btn.textContent = 'যাচ্ছেন...';
    const data = await apiPost(`/api/room/${ROOM_CODE}/leave`);
    if (data.success) {
        window.location.href = data.redirect;
    } else {
        showToast(data.message || 'ত্রুটি হয়েছে', 'error');
        btn.disabled = false;
        btn.textContent = 'হ্যাঁ, চলে যান';
        closeLeaveModal();
    }
}
document.getElementById('leaveModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeLeaveModal();
});
</script>
@endpush
