@extends('errors.layout')

@section('title', '500 — Server Error')

@section('extra-style')
<style>
    .icon-wrap { background: linear-gradient(135deg, #fef2f2, #fecaca); }
    .shake { animation: shake 0.6s cubic-bezier(.36,.07,.19,.97) both; }
    @keyframes shake {
        10%, 90%  { transform: translateX(-2px); }
        20%, 80%  { transform: translateX(4px); }
        30%, 50%, 70% { transform: translateX(-6px); }
        40%, 60%  { transform: translateX(6px); }
    }
</style>
@endsection

@section('body')
    <div class="icon-wrap shake">
        <i class="fas fa-triangle-exclamation" style="font-size:2.5rem; color:#ef4444;"></i>
    </div>

    <div style="display:inline-block; background:#fef2f2; color:#dc2626; font-size:0.65rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; padding:0.3rem 0.9rem; border-radius:999px; margin-bottom:1rem;">
        Error 500
    </div>

    <h1>Something Went Wrong</h1>
    <p>Our team has been notified and is working on a fix. Please try again in a moment — your learning journey is important to us.</p>

    <div class="btn-group">
        <a href="javascript:location.reload()" class="btn btn-primary">
            <i class="fas fa-rotate-right"></i> Try Again
        </a>
        <a href="/" class="btn btn-ghost">
            <i class="fas fa-home"></i> Go Home
        </a>
    </div>
@endsection
