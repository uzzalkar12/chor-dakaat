@php
    $sorted = $room->players->sortByDesc('total_score')->values();
@endphp
<div class="game-over">
    <div style="font-size:3rem">🏆</div>
    <h2>গেম শেষ!</h2>
    <p style="color:var(--text-muted)">
        বিজয়ী: <strong style="color:var(--accent-gold)">{{ $sorted->first()->user->name }}</strong>
    </p>

    <div class="final-scores">
        @foreach($sorted as $idx => $p)
            @php $rank = $idx + 1; @endphp
            <div class="final-score-row {{ $rank===1?'first-place':'' }}">
                <div class="rank-badge rank-{{ $rank }}">{{ $rank }}</div>
                <div style="flex:1;font-weight:{{ $rank===1?'700':'400' }}">
                    {{ $p->user->name }}
                    @if($p->user_id == $userId) <small style="color:var(--text-muted)">(আপনি)</small> @endif
                </div>
                <div style="font-size:1.2rem;font-weight:700;color:{{ $rank===1?'var(--accent-gold)':'var(--text-main)' }}">
                    {{ $p->total_score }}
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:center">
        <a href="{{ route('lobby') }}" class="btn btn-secondary">🏠 লবিতে ফিরুন</a>
    </div>
</div>
