@extends('layouts.app')

@section('title', 'Lupa Password - Partlyfe')

@push('styles')
<style>
.auth-wrapper {
    min-height: calc(100vh - 64px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
.auth-card {
    background: var(--surface);
    border: 1px solid rgba(242, 239, 230, 0.08);
    border-radius: 4px;
    padding: 3rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
.auth-title {
    font-family: var(--font-display);
    font-size: 1.8rem;
    letter-spacing: 0.05em;
    color: var(--off-white);
    text-align: center;
    margin-bottom: 0.5rem;
}
.auth-title span { color: var(--orange); }
.auth-desc {
    font-size: 0.85rem;
    color: var(--gray-mid);
    text-align: center;
    margin-bottom: 2rem;
    line-height: 1.5;
}
.form-group { margin-bottom: 1.5rem; }
.form-label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: var(--gray-mid); }
.auth-input {
    width: 100%;
    padding: 0.75rem 1rem;
    box-sizing: border-box;
    background: #1A1A16;
    border: 1px solid rgba(242, 239, 230, 0.1);
    color: var(--off-white);
    border-radius: 2px;
    font-family: var(--font-body);
    font-size: 0.88rem;
    outline: none;
    transition: border-color 0.2s;
}
.auth-input:focus { border-color: var(--orange); }
.auth-btn {
    width: 100%;
    padding: 1rem;
    background: var(--orange);
    color: white;
    border: none;
    border-radius: 2px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
}
.auth-btn:hover { background: var(--orange-light); }
.auth-link {
    display: block;
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.85rem;
    color: var(--gray-mid);
}
.auth-link a { color: var(--orange); text-decoration: none; }
.alert-success {
    background: rgba(46, 204, 113, 0.1);
    border: 1px solid rgba(46, 204, 113, 0.2);
    color: var(--green);
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
}
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">LUPA <span>PASSWORD</span></h1>
        <p class="auth-desc">Masukkan email Anda untuk menerima tautan reset kata sandi.</p>

        @if(session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="auth-input" placeholder="contoh@email.com" required value="{{ old('email') }}" autofocus>
                @error('email')
                    <div style="color: var(--red); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="auth-btn">KIRIM TAUTAN RESET</button>

            <div class="auth-link">
                <a href="{{ route('login') }}">← Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>
@endsection