<div class="waiting-screen" id="waitingScreen">
    <h2>⏳ খেলোয়াড়দের জন্য অপেক্ষা</h2>
    <p id="waitCount">{{ $room->players->count() }}/4 জন যোগ দিয়েছেন</p>

    <div class="player-slots" id="waitSlots">
        @for($i = 1; $i <= 4; $i++)
            @php $p = $room->players->firstWhere('seat_number', $i); @endphp
            <div class="slot-card {{ $p ? 'filled' : '' }}"
                 id="wslot-{{ $i }}"
                 data-seat="{{ $i }}">
                <div class="s-emoji">{{ $p ? '👤' : '❓' }}</div>
                <div class="s-name">{{ $p ? $p->user->name : 'খালি' }}</div>
            </div>
        @endfor
    </div>

    @if($isHost)
        <button id="startBtn" class="btn btn-gold"
            {{ $room->players->count() < 4 ? 'disabled' : '' }}
            onclick="startGame()">
            🎮 গেম শুরু করুন
        </button>
        <p id="waitHint" style="color:var(--text-muted);margin-top:0.75rem;font-size:0.85rem;
            {{ $room->players->count() >= 4 ? 'display:none' : '' }}">
            আরও {{ 4 - $room->players->count() }} জন লাগবে
        </p>
    @else
        <div class="waiting-dots" style="margin-top:1.5rem">
            <span></span><span></span><span></span>
        </div>
        <p style="color:var(--text-muted);margin-top:0.75rem;font-size:0.9rem">হোস্ট গেম শুরু করার অপেক্ষায়...</p>
    @endif
</div>
