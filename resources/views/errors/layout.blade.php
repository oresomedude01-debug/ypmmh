<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') — YPMMH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            background-image:
                radial-gradient(ellipse at 20% 20%, rgba(11,77,115,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(99,102,241,0.06) 0%, transparent 60%);
            padding: 2rem;
            overflow: hidden;
        }
        /* Decorative blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 { width: 500px; height: 500px; background: rgba(11,77,115,0.07); top: -150px; left: -150px; }
        .blob-2 { width: 400px; height: 400px; background: rgba(99,102,241,0.06); bottom: -100px; right: -100px; }
        .blob-3 { width: 200px; height: 200px; background: rgba(245,158,11,0.05); top: 50%; left: 60%; }

        .card {
            position: relative;
            z-index: 1;
            background: white;
            border-radius: 2.5rem;
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px -10px rgba(15,23,42,0.12), 0 0 0 1px rgba(15,23,42,0.04);
            animation: slideUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .code-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 96px;
            height: 96px;
            border-radius: 1.5rem;
            font-size: 2rem;
            font-weight: 900;
            margin: 0 auto 1.5rem;
            letter-spacing: -2px;
        }

        .icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 96px;
            height: 96px;
            border-radius: 1.5rem;
            margin: 0 auto 1.5rem;
        }

        h1 { font-size: 1.6rem; font-weight: 900; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.03em; }
        p  { font-size: 0.9rem; color: #64748b; line-height: 1.65; margin-bottom: 2rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1.75rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #0B4D73, #1d6fa4);
            color: white;
            box-shadow: 0 4px 20px rgba(11,77,115,0.25);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(11,77,115,0.35); }
        .btn-ghost {
            background: #f1f5f9;
            color: #475569;
        }
        .btn-ghost:hover { background: #e2e8f0; }

        .btn-group { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            color: #0B4D73;
            font-weight: 900;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            text-decoration: none;
            opacity: 0.6;
        }
    </style>
    @yield('extra-style')
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="card">
        <a href="/" class="logo">
            <i class="fas fa-mosque"></i> YPMMH
        </a>

        @yield('body')

        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #f1f5f9;">
            <p style="font-size:0.72rem; margin-bottom:0; color:#94a3b8;">
                Young Productive Muslim Mentorship Hub &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>
