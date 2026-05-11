@extends('errors.layout')

@section('title', '404 — Page Not Found')

@section('extra-style')
<style>
    .icon-wrap { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
    .floating { animation: floating 3s ease-in-out infinite; }
    @keyframes floating {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
    }
</style>
@endsection

@section('body')
    <div class="icon-wrap floating">
        <i class="fas fa-compass" style="font-size:2.5rem; color:#3b82f6;"></i>
    </div>

    <div style="display:inline-block; background:#eff6ff; color:#2563eb; font-size:0.65rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; padding:0.3rem 0.9rem; border-radius:999px; margin-bottom:1rem;">
        Error 404
    </div>

    <h1>Lost in the Journey</h1>
    <p>The page you're looking for has moved, was renamed, or never existed. Don't worry — your learning journey continues from here.</p>

    <div class="btn-group">
        <a href="/" class="btn btn-primary">
            <i class="fas fa-home"></i> Go Home
        </a>
        <a href="javascript:history.back()" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
    </div>
@endsection
