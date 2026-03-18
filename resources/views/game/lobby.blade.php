@extends('layouts.app')
@section('title', 'লবি')

@push('styles')
<style>
.lobby-hero {
    text-align: center;
    padding: 3rem 1rem 2rem;
}
.lobby-hero h1 {
    font-family: 'Tiro Bangla', serif;
    font-size: 2.5rem;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-red));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}
.lobby-hero p { color: var(--text-muted); font-size: 1rem; }

.lobby-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    max-width: 700px;
    margin: 0 auto;
}
@media(max-width: 600px) { .lobby-grid { grid-template-columns: 1fr; } }

.lobby-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow);
}
.lobby-card h2 {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.round-selector {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.round-btn {
    padding: 0.4rem 0.9rem;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg-glass);
    color: var(--text-muted);
    cursor: pointer;
    font-family: inherit;
    font-size: 0.9rem;
    transition: all 0.2s;
}
.round-btn.active, .round-btn:hover {
    border-color: var(--accent-gold);
    color: var(--accent-gold);
    background: rgba(244,165,34,0.1);
}

.rules-section {
    max-width: 700px;
    margin: 2rem auto 0;
}
.rules-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-top: 1.2rem;
}
@media(max-width: 600px) { .rules-grid { grid-template-columns: repeat(2, 1fr); } }

.role-card {
    background: var(--bg-glass);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.2rem 1rem;
    text-align: center;
}
.role-card .emoji { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
.role-card .name  { font-size: 1.1rem; font-weight: 600; display: block; margin-bottom: 0.2rem; }
.role-card .pts   { font-size: 0.85rem; color: var(--text-muted); }
.role-card.chor   { border-color: rgba(230,57,70,0.3); }
.role-card.daakat { border-color: rgba(199,125,255,0.3); }
.role-card.police { border-color: rgba(78,168,222,0.3); }
.role-card.babu   { border-color: rgba(244,165,34,0.3); }

.rules-text {
    margin-top: 1.2rem;
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.8;
}
.rules-text li { margin-bottom: 0.4rem; }
</style>
@endpush

@section('content')
<div class="lobby-hero">
    <h1>🎮 চোর-ডাকাত-পুলিশ-বাবু</h1>
    <p>বাংলাদেশের জনপ্রিয় কার্ড গেম — অনলাইনে ৪ জনের সাথে খেলুন</p>
</div>

@if ($errors->any())
    <div class="alert-error" style="max-width:700px;margin:0 auto 1rem;">
        @foreach ($errors->all() as $error) {{ $error }} @endforeach
    </div>
@endif

<div class="lobby-grid">
    <!-- Create Room -->
    <div class="lobby-card">
        <h2>🏠 নতুন রুম তৈরি করুন</h2>
        <form action="{{ route('room.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">মোট রাউন্ড</label>
                <div class="round-selector" id="roundSelector">
                    @foreach([6,10,15,20] as $r)
                        <button type="button" class="round-btn {{ $r==6?'active':'' }}"
                            onclick="selectRounds({{ $r }}, this)">{{ $r }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="total_rounds" id="totalRoundsInput" value="6">
            </div>
            <button type="submit" class="btn btn-primary btn-full">
                🏠 রুম তৈরি করুন
            </button>
        </form>
    </div>

    <!-- Join Room -->
    <div class="lobby-card">
        <h2>🔗 রুমে যোগ দিন</h2>
        <form action="{{ route('room.join') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">রুম কোড</label>
                <input type="text" name="room_code" class="form-input"
                    placeholder="৬ অক্ষরের কোড"
                    maxlength="6" style="text-transform:uppercase;letter-spacing:0.15em;font-size:1.2rem;"
                    required>
            </div>
            <button type="submit" class="btn btn-secondary btn-full" style="margin-top:0.9rem">
                🚀 যোগ দিন
            </button>
        </form>
    </div>
</div>

<!-- Rules -->
<div class="rules-section">
    <div class="lobby-card">
        <h2>📜 খেলার নিয়ম</h2>
        <div class="rules-grid">
            <div class="role-card chor">
                <span class="emoji">🦹</span>
                <span class="name role-chor">চোর</span>
                <span class="pts">৪০ পয়েন্ট</span>
            </div>
            <div class="role-card daakat">
                <span class="emoji">💀</span>
                <span class="name role-daakat">ডাকাত</span>
                <span class="pts">৬০ পয়েন্ট</span>
            </div>
            <div class="role-card police">
                <span class="emoji">👮</span>
                <span class="name role-police">পুলিশ</span>
                <span class="pts">৮০ পয়েন্ট</span>
            </div>
            <div class="role-card babu">
                <span class="emoji">🎩</span>
                <span class="name role-babu">বাবু</span>
                <span class="pts">১০০ পয়েন্ট</span>
            </div>
        </div>
        <ul class="rules-text">
            <li>🎯 প্রতি রাউন্ডে ৪টি রোল র‍্যান্ডমলি বিতরণ হয়</li>
            <li>📋 বিজোড় রাউন্ড (১,৩,৫...) = <strong class="role-chor">চোর</strong> রাউন্ড — পুলিশ চোর খোঁজে</li>
            <li>📋 জোড় রাউন্ড (২,৪,৬...) = <strong class="role-daakat">ডাকাত</strong> রাউন্ড — পুলিশ ডাকাত খোঁজে</li>
            <li>✅ পুলিশ সঠিকভাবে ধরলে: পুলিশ ৮০, ধরা পড়া ব্যক্তি ০ পয়েন্ট পায়</li>
            <li>❌ পুলিশ ভুল করলে: পুলিশ ০ পয়েন্ট পায়</li>
            <li>🎩 বাবু সর্বদা ১০০ পয়েন্ট পায়</li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectRounds(count, btn) {
    document.querySelectorAll('.round-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('totalRoundsInput').value = count;
}
</script>
@endpush
