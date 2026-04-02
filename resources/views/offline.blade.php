<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Offline - YPMMH</title>
    <meta name="theme-color" content="#0B4D73">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #0B4D73;
            --primary-light: #0ea5e9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Background grid pattern */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(11, 77, 115, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(11, 77, 115, 0.04) 1px, transparent 1px);
            z-index: -2;
            pointer-events: none;
        }

        /* Decorative blobs */
        body::after {
            content: '';
            position: fixed;
            top: -20%;
            right: -15%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(11, 77, 115, 0.08) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        .container {
            text-align: center;
            max-width: 480px;
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animated offline icon */
        .offline-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .offline-icon svg {
            width: 100%;
            height: 100%;
        }

        .pulse-ring {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px solid rgba(11, 77, 115, 0.15);
            animation: pulse 2s ease-in-out infinite;
        }

        .pulse-ring:nth-child(2) {
            animation-delay: 0.5s;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: 0;
            }
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        p {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .btn-retry {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(11, 77, 115, 0.25);
            text-decoration: none;
        }

        .btn-retry:hover {
            background: #063b59;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(11, 77, 115, 0.35);
        }

        .btn-retry:active {
            transform: translateY(0);
        }

        .btn-retry svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }

        .btn-retry:hover svg {
            transform: rotate(180deg);
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            margin-right: 6px;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        .status-bar {
            margin-top: 2.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cached-pages {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .cached-pages h3 {
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }

        .cached-pages a {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            background: rgba(11, 77, 115, 0.07);
            color: var(--primary);
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .cached-pages a:hover {
            background: rgba(11, 77, 115, 0.15);
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="offline-icon">
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="60" cy="60" r="55" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="2" />
                <path d="M60 30 C60 30, 90 50, 90 70 C90 85, 75 95, 60 95 C45 95, 30 85, 30 70 C30 50, 60 30, 60 30Z"
                    fill="#0B4D73" opacity="0.1" />
                <g transform="translate(35, 40)">
                    <path
                        d="M25 0 L25 0 C30 5, 40 15, 40 25 C40 33, 33 40, 25 40 C17 40, 10 33, 10 25 C10 15, 20 5, 25 0Z"
                        fill="#0B4D73" opacity="0.6" />
                    <line x1="5" y1="5" x2="45" y2="45" stroke="#ef4444" stroke-width="3" stroke-linecap="round" />
                    <line x1="45" y1="5" x2="5" y2="45" stroke="#ef4444" stroke-width="3" stroke-linecap="round" />
                </g>
            </svg>
        </div>

        <h1>You're Offline</h1>
        <p>
            It looks like you've lost your internet connection.
            Don't worry — check your connection and try again.
            Some pages you've visited before may still be available.
        </p>

        <button class="btn-retry" onclick="window.location.reload()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            Try Again
        </button>

        <div class="cached-pages">
            <h3>Try these pages</h3>
            <a href="/">Home</a>
            <a href="/about">About</a>
            <a href="/programs/explore">Programs</a>
            <a href="/blog">Blog</a>
        </div>

        <div class="status-bar">
            <span class="status-dot"></span>
            No Internet Connection
        </div>
    </div>

    <script>
        // Auto-retry when connection is restored
        window.addEventListener('online', () => {
            window.location.reload();
        });
    </script>
</body>

</html>