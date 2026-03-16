@if($currentRound && $myAssignment)
    @php
        $role = $myAssignment->role;
        $emoji = ['chor'=>'🦹','daakat'=>'💀','police'=>'👮','babu'=>'🎩'][$role];
        $nameBn = ['chor'=>'চোর','daakat'=>'ডাকাত','police'=>'পুলিশ','babu'=>'বাবু'][$role];
        $pts = ['chor'=>40,'daakat'=>60,'police'=>80,'babu'=>100][$role];
        $roundTypeBn = $currentRound->round_type === 'chor' ? 'চোর' : 'ডাকাত';
    @endphp

    @if($currentRound->status === 'police_guessing')
        <div class="my-role-section">
            <div style="color:var(--text-muted);font-size:0.85rem;margin-bottom:0.5rem">এই রাউন্ডে আপনার রোল</div>
            <div class="my-role-card {{ $role }}">
                <span class="role-big-emoji">{{ $emoji }}</span>
                <div class="role-big-name role-{{ $role }}">{{ $nameBn }}</div>
                <div class="role-big-pts">{{ $pts }} পয়েন্ট</div>
            </div>
        </div>

        @if($isPolice)
            <div class="police-section" style="margin-top:1rem">
                <h3>👮 {{ $roundTypeBn }}কে চিহ্নিত করুন</h3>
                <div class="guess-grid">
                    @foreach($room->players->where('user_id', '!=', $userId) as $p)
                        <button class="guess-btn" onclick="submitGuess({{ $p->user_id }}, this)">
                            <span class="g-name">{{ $p->user->name }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="police-section" style="margin-top:1rem;border-color:var(--border)">
                <div class="waiting-police">
                    <div class="pulse-icon">👮</div>
                    <p style="margin-top:0.8rem">পুলিশ সিদ্ধান্ত নিচ্ছেন...</p>
                </div>
            </div>
        @endif

    @elseif($currentRound->status === 'completed')
        {{-- Round just completed, show results --}}
        @php
            $correct = $currentRound->police_correct;
            $targetBn = $currentRound->round_type === 'chor' ? 'চোর' : 'ডাকাত';
        @endphp
        <div class="round-result">
            <div class="result-banner {{ $correct ? 'correct' : 'wrong' }}">
                {{ $correct ? "✅ পুলিশ {$targetBn}কে ধরে ফেলেছে!" : "❌ পুলিশ ভুল করেছে!" }}
            </div>
            <table class="results-table">
                <thead><tr><th>খেলোয়াড়</th><th>রোল</th><th>পয়েন্ট</th></tr></thead>
                <tbody>
                    @foreach($currentRound->assignments as $a)
                        <tr>
                            <td>{{ $a->user->name }} @if($a->user_id == $userId)<small>(আপনি)</small>@endif</td>
                            <td class="role-{{ $a->role }}">{{ $a->getBengaliRoleName() }}</td>
                            <td class="pts-earned {{ $a->points_earned == 0 ? 'pts-zero' : 'pts-positive' }}">
                                {{ $a->points_earned > 0 ? '+' : '' }}{{ $a->points_earned }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="text-align:center">
                @if($isHost)
                    <button class="btn btn-gold" onclick="startNextRound(this)" style="margin-top:1.5rem">▶️ পরবর্তী রাউন্ড</button>
                @else
                    <p style="color:var(--text-muted);margin-top:1.5rem;font-size:0.9rem">হোস্ট পরবর্তী রাউন্ড শুরু করার অপেক্ষায়...</p>
                @endif
            </div>
        </div>
    @endif
@else
    <div class="waiting-screen">
        <h2>⏳ রাউন্ড শুরু হচ্ছে...</h2>
        <div class="waiting-dots"><span></span><span></span><span></span></div>
    </div>
@endif
