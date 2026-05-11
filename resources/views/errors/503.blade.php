@extends('errors.layout')

@section('title', '503 — Under Maintenance')

@section('extra-style')
<style>
    .icon-wrap { background: linear-gradient(135deg, #f5f3ff, #ddd6fe); }
    .spin-slow { animation: spin 4s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection

@section('body')
    <div class="icon-wrap">
        <i class="fas fa-gear spin-slow" style="font-size:2.5rem; color:#7c3aed;"></i>
    </div>

    <div style="display:inline-block; background:#f5f3ff; color:#6d28d9; font-size:0.65rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; padding:0.3rem 0.9rem; border-radius:999px; margin-bottom:1rem;">
        Maintenance Mode
    </div>

    <h1>We're Polishing Things Up</h1>
    <p>YPMMH is currently undergoing scheduled maintenance to bring you a better experience. We'll be back very soon — JazakAllahu Khayran for your patience.</p>

    <div class="btn-group">
        <a href="javascript:location.reload()" class="btn btn-primary">
            <i class="fas fa-rotate-right"></i> Refresh Page
        </a>
    </div>

    <script>
        // Auto-refresh every 60 seconds during maintenance
        setTimeout(() => location.reload(), 60000);
    </script>
@endsection
