@extends('errors.layout')

@section('title', '419 — Session Expired')

@section('extra-style')
<style>
    .icon-wrap { background: linear-gradient(135deg, #fefce8, #fef08a); }
    .countdown-ring {
        width: 48px; height: 48px;
        border-radius: 50%;
        border: 3px solid #fef08a;
        border-top-color: #eab308;
        animation: spin 1s linear infinite;
        margin: 0 auto 0.5rem;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection

@section('body')
    <div class="icon-wrap">
        <i class="fas fa-clock-rotate-left" style="font-size:2.5rem; color:#ca8a04;"></i>
    </div>

    <div style="display:inline-block; background:#fefce8; color:#a16207; font-size:0.65rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; padding:0.3rem 0.9rem; border-radius:999px; margin-bottom:1rem;">
        Session Expired
    </div>

    <h1>Your Session Ended</h1>
    <p>For your security, your session has expired. We're taking you back to the login page in <strong id="countdown">5</strong> seconds.</p>

    <div class="countdown-ring"></div>

    <div class="btn-group" style="margin-top:1.5rem;">
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Sign In Now
        </a>
        <a href="/" class="btn btn-ghost">
            <i class="fas fa-home"></i> Home
        </a>
    </div>

    <script>
        let secs = 5;
        const el = document.getElementById('countdown');
        const timer = setInterval(() => {
            secs--;
            if (el) el.textContent = secs;
            if (secs <= 0) {
                clearInterval(timer);
                window.location.href = '{{ route("login") }}';
            }
        }, 1000);
    </script>
@endsection
