<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ app_name() }}</title>
    <link rel="icon" type="image/png" href="{{ app_favicon() }}">

    @include('partials.pwa')

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0B4D73',
                        secondary: '#075985',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-hue: 202;
            --primary-sat: 83%;
            --primary-light: 25%;
            --primary-color: #0B4D73;
            --primary-500: #0B4D73;
            --bg-primary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --shadow-color: rgba(11, 77, 115, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #020617;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --glass-bg: rgba(15, 23, 42, 0.8);
            --glass-border: rgba(30, 41, 59, 0.5);
            --shadow-color: rgba(0, 0, 0, 0.4);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Grid Background Pattern */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(11, 77, 115, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(11, 77, 115, 0.05) 1px, transparent 1px);
            z-index: -2;
            pointer-events: none;
        }

        /* Abstract Shapes */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.5;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(11, 77, 115, 0.15) 0%, rgba(11, 77, 115, 0) 70%);
            top: -100px;
            right: -100px;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, rgba(6, 182, 212, 0) 70%);
            bottom: -50px;
            left: -50px;
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px var(--shadow-color);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0B4D73 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Floating Input Styles */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.875rem;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(11, 77, 115, 0.1);
        }

        .form-label {
            position: absolute;
            left: 1rem;
            top: 1rem;
            color: var(--text-secondary);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.7;
            font-size: 0.875rem;
        }

        .form-input:focus~.form-label,
        .form-input:not(:placeholder-shown)~.form-label {
            top: -0.65rem;
            left: 0.8rem;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--primary-color);
            background: var(--bg-primary);
            padding: 0 0.5rem;
            border-radius: 4px;
            opacity: 1;
        }

        /* Animation */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-auth {
            animation: fadeInScale 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .glass-hover {
            transition: all 0.3s ease;
        }

        .glass-hover:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Background Elements -->
    <div class="bg-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    @yield('content')

    <script>
        // Auto-apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    @yield('scripts')

    {{-- PWA Install Prompt --}}
    @include('partials.pwa-install-prompt')
</body>

</html>