@extends('layouts.app')
@section('title', 'রেজিস্ট্রেশন')

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
.auth-subtitle { text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; }
.auth-footer { text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem; }
.auth-footer a { color: var(--accent-blue); text-decoration: none; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">নতুন অ্যাকাউন্ট</h1>
        <p class="auth-subtitle">খেলতে শুরু করতে রেজিস্ট্রেশন করুন</p>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error) {{ $error }}<br>@endforeach
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">নাম</label>
                <input type="text" name="name" class="form-input"
                    placeholder="আপনার নাম" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">ইমেইল</label>
                <input type="email" name="email" class="form-input"
                    placeholder="আপনার ইমেইল" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড</label>
                <input type="password" name="password" class="form-input"
                    placeholder="পাসওয়ার্ড" required>
            </div>
            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                <input type="password" name="password_confirmation" class="form-input"
                    placeholder="আবার পাসওয়ার্ড দিন" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full" style="margin-top:0.5rem">
                রেজিস্ট্রেশন করুন
            </button>
        </form>

        <div class="auth-footer">
            আগে থেকেই অ্যাকাউন্ট আছে? <a href="{{ route('login') }}">লগইন করুন</a>
        </div>
    </div>
</div>
@endsection
