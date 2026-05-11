@extends('errors.layout')

@section('title', '401 — Unauthenticated')

@section('extra-style')
<style>
    .icon-wrap { background: linear-gradient(135deg, #f0fdf4, #bbf7d0); }
</style>
@endsection

@section('body')
    <div class="icon-wrap">
        <i class="fas fa-lock" style="font-size:2.5rem; color:#16a34a;"></i>
    </div>

    <div style="display:inline-block; background:#f0fdf4; color:#15803d; font-size:0.65rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; padding:0.3rem 0.9rem; border-radius:999px; margin-bottom:1rem;">
        Error 401
    </div>

    <h1>Authentication Required</h1>
    <p>You must be signed in to access this page. Please log in with your account credentials to continue.</p>

    <div class="btn-group">
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Sign In
        </a>
        <a href="/" class="btn btn-ghost">
            <i class="fas fa-home"></i> Go Home
        </a>
    </div>
@endsection
