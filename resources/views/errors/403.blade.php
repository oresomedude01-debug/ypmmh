@extends('errors.layout')

@section('title', '403 — Access Forbidden')

@section('extra-style')
<style>
    .icon-wrap { background: linear-gradient(135deg, #fff7ed, #fed7aa); }
    .pulse { animation: pulse 2s cubic-bezier(0.4,0,0.6,1) infinite; }
    @keyframes pulse { 0%, 100% { opacity:1; } 50% { opacity:.7; } }
</style>
@endsection

@section('body')
    <div class="icon-wrap pulse">
        <i class="fas fa-shield-halved" style="font-size:2.5rem; color:#f97316;"></i>
    </div>

    <div style="display:inline-block; background:#fff7ed; color:#ea580c; font-size:0.65rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; padding:0.3rem 0.9rem; border-radius:999px; margin-bottom:1rem;">
        Error 403
    </div>

    <h1>Access Restricted</h1>
    <p>You don't have permission to view this page. If you believe this is a mistake, please contact the administration team.</p>

    <div class="btn-group">
        <a href="/" class="btn btn-primary">
            <i class="fas fa-home"></i> Go Home
        </a>
        <a href="javascript:history.back()" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
    </div>
@endsection
