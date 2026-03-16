@extends('layouts.app')
@section('title', 'লগইন')

@push('styles')
<style>
.auth-wrap {
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    margin: -2rem;
}
.auth-card {
    width: 100%;
    max-width: 420px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
.auth-title {
    font-family: 'Tiro Bangla', serif;
    font-size: 1.8rem;
    text-align: center;
    margin-bottom: 0.4rem;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-red));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.auth-subtitle {
    text-align: center;
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 2rem;
}
.auth-footer {
    text-align: center;
    margin-top: 1.5rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}
.auth-footer a { color: var(--accent-blue); text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }
.role-icons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}
.role-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    border: 1px solid var(--border);
    background: var(--bg-glass);
}
.role-icon span { font-size: 0.6rem; margin-top: 2px; color: var(--text-muted); }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="role-icons">
            <div class="role-icon role-chor">🦹<span>চোর</span></div>
            <div class="role-icon role-daakat">💀<span>ডাকাত</span></div>
            <div class="role-icon role-police">👮<span>পুলিশ</span></div>
            <div class="role-icon role-babu">🎩<span>বাবু</span></div>
        </div>

        <h1 class="auth-title">স্বাগতম!</h1>
        <p class="auth-subtitle">আপনার অ্যাকাউন্টে লগইন করুন</p>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error) {{ $error }} @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">ইমেইল</label>
                <input type="email" name="email" class="form-input"
                    placeholder="আপনার ইমেইল" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড</label>
                <input type="password" name="password" class="form-input"
                    placeholder="পাসওয়ার্ড দিন" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full" style="margin-top:0.5rem">
                লগইন করুন
            </button>
        </form>

        <div class="auth-footer">
            নতুন ব্যবহারকারী? <a href="{{ route('register') }}">রেজিস্ট্রেশন করুন</a>
        </div>
    </div>
</div>
@endsection
