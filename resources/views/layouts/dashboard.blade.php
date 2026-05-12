<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>

    @include('partials.pwa')

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Primary Color - Customize this */
            --primary-hue: 260;
            --primary-sat: 80%;
            --primary-light: 60%;

            --primary-500: #0B4D73;
            --primary-600: #075985;

            /* Light Theme */
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: rgba(203, 213, 225, 0.6);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.8);
            --shadow-color: rgba(11, 77, 115, 0.08);
            /* Using primary color hint in shadow */
        }

        [data-theme="dark"] {
            /* Deeper, more premium Midnight Dark Theme */
            --bg-primary: #050b1a;
            /* Deepest blue/black */
            --bg-secondary: #0d1526;
            /* Slightly lighter deep navy */
            --text-primary: #f8fafc;
            /* Very light slate */
            --text-secondary: #94a3b8;
            /* Muted slate */
            --border-color: rgba(30, 41, 59, 0.7);
            --glass-bg: rgba(13, 21, 38, 0.8);
            --glass-border: rgba(51, 65, 85, 0.4);
            --shadow-color: rgba(0, 0, 0, 0.6);

            /* Brighter primary for better contrast in dark mode */
            --primary-500: #0ea5e9;
            /* Sky 500 */
            --primary-600: #0284c7;
            /* Sky 600 */

            /* Enhanced shadows for dark mode */
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.5);
            /* Glow effect for primary elements in dark mode */
            --glow-color: rgba(14, 165, 233, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Glassmorphism Effect */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 var(--shadow-color);
        }

        .glass-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-hover:hover {
            background: rgba(var(--primary-hue), var(--primary-sat), var(--primary-light), 0.1);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 var(--shadow-color);
        }

        /* Admin Card Styling */
        .admin-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px var(--shadow-color), 0 2px 4px -1px var(--shadow-color);
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px var(--shadow-color), 0 4px 6px -2px var(--shadow-color);
        }

        [data-theme="dark"] .admin-card {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            box-shadow: var(--card-shadow);
        }

        /* Admin Table Header */
        .admin-table-header {
            background: var(--bg-primary);
            color: var(--text-secondary);
            font-weight: 700;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        [data-theme="dark"] .admin-table-header {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-500);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-600);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* Page Transition */
        .page-transition {
            animation: slideIn 0.4s ease-out;
        }

        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg,
                    var(--glass-bg) 25%,
                    rgba(255, 255, 255, 0.3) 50%,
                    var(--glass-bg) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            animation: slideInFromRight 0.3s ease-out;
        }

        @keyframes slideInFromRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Modal Overlay */
        .modal-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                /* Stop above the bottom navigation bar (~64px) */
                height: calc(100dvh - 64px);
                height: calc(100vh - 64px); /* fallback for browsers without dvh */
                /* Account for safe-area (notched phones) */
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        /* Active Menu Item */
        .menu-item {
            color: var(--text-primary);
        }

        .menu-item.active {
            background: var(--primary-500);
            color: white;
        }

        /* Dropdown */
        .dropdown-content {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease-out;
        }

        .dropdown-content.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
            animation: fadeIn 0.2s ease-out;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background: var(--glass-bg);
            backdrop-filter: blur(8px);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: rgba(var(--primary-hue), var(--primary-sat), var(--primary-light), 0.05);
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .btn-primary {
            background: var(--primary-500);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: var(--glass-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--glass-border);
            transform: translateY(-2px);
        }

        /* Input Styles */
        input,
        select,
        textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: var(--glass-bg);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(var(--primary-hue), var(--primary-sat), var(--primary-light), 0.1);
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Focus Visible */
        *:focus-visible {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
        }

        /* Navbar scroll fade effect */
        #dashboard-navbar {
            opacity: 1;
            transition: opacity 0.4s ease-in-out;
        }

        #dashboard-navbar.navbar-hidden {
            opacity: 0;
        }

        /* Desktop/Table Shared Styles */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* --- Global Mobile Refinements (Across App) --- */
        @media (max-width: 767px) {
            /* 1. Global Typography Scaling */
            h1, .text-3xl, .text-4xl, .text-2xl { 
                font-size: 1.25rem !important; 
                line-height: 1.75rem !important; 
                letter-spacing: -0.01em !important;
            }
            h2, .text-xl { 
                font-size: 1.05rem !important; 
                line-height: 1.5rem !important; 
            }
            h3, .text-lg { 
                font-size: 0.95rem !important; 
                line-height: 1.4rem !important; 
            }
            p, .text-sm, .text-base { 
                font-size: 0.8125rem !important; 
                line-height: 1.35 !important; 
            }
            .text-xs, label, .uppercase {
                font-size: 0.6875rem !important;
                letter-spacing: 0.025em !important;
            }

            /* 2. Proportional Component Scaling */
            .p-6, .p-8, .p-10 { padding: 1.125rem !important; }
            .p-4 { padding: 0.875rem !important; }
            .px-6, .px-8 { padding-left: 1rem !important; padding-right: 1rem !important; }
            .py-6, .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            
            .gap-6, .gap-8, .gap-10 { gap: 1rem !important; }
            .gap-4 { gap: 0.75rem !important; }

            .space-y-6, .space-y-8, .space-y-10 { margin-top: 1rem !important; }
            .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem !important; }
            .space-y-8 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.25rem !important; }

            /* 3. Button & Input Optimization */
            .btn { 
                padding: 0.5rem 0.875rem !important; 
                font-size: 0.75rem !important; 
                border-radius: 0.625rem !important;
            }
            input, select, textarea {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8125rem !important;
                border-radius: 0.625rem !important;
            }

            /* 4. Layout Hierarchy Fixes */
            .main-content {
                margin-left: 0;
                padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
            }

            .admin-card { 
                border-radius: 0.875rem !important; 
                box-shadow: 0 2px 4px rgba(0,0,0,0.04) !important;
            }
            .main-content {
                padding-bottom: 5rem !important; /* Extra space for bottom nav */
            }
            
            /* Hidden elements on ultra-small screens */
            @media (max-width: 400px) {
                .hide-on-mobile-xs { display: none !important; }
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Toast Container -->
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        @include('partials.navbar')

        <!-- Page Content -->
        <main class="p-6 min-h-screen page-transition relative" id="ajax-content">
            @if(auth()->check() && !auth()->user()->hasVerifiedEmail())
                <div class="mb-6 bg-gradient-to-r from-red-600 to-orange-500 rounded-2xl p-4 sm:p-5 text-white shadow-lg flex flex-col sm:flex-row items-center gap-4 justify-between animate-slide-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <i class="fas fa-envelope-open-text text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-black tracking-wide text-sm leading-tight">Verify Your Email Address</h3>
                            <p class="text-white/80 text-xs font-medium mt-0.5">Please check your inbox (and spam folder) for the verification link.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-white text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-colors shadow-sm active:scale-95 whitespace-nowrap">
                            Resend Verification
                        </button>
                    </form>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-[999] hidden md:hidden"
        onclick="toggleSidebar()"></div>

    <!-- Mobile Bottom Navigation -->
    @include('partials.mobile-bottom-nav')

    <!-- Core JavaScript -->
    <script>
        // Theme Management
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);

        function toggleTheme() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
            showToast(`Switched to ${newTheme} mode`, 'success');
        }

        function updateThemeIcon(theme) {
            const icon = themeToggle.querySelector('i');
            if (theme === 'dark') {
                icon.className = 'fas fa-sun';
            } else {
                icon.className = 'fas fa-moon';
            }
        }

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth < 768) {
                // Mobile: slide sidebar
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('hidden');
            } else {
                // Desktop: collapse sidebar
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }

        // Close sidebar when clicking outside on mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.add('hidden');
            }
        });

        // Dropdown Management
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const allDropdowns = document.querySelectorAll('.dropdown-content');

            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.remove('show');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-content').forEach(d => {
                    d.classList.remove('show');
                });
            }
        });

        // Toast Notification System
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast glass rounded-lg p-4 mb-3 flex items-center gap-3 ${type}`;
            toast.setAttribute('role', 'alert');

            const icons = {
                success: 'fa-check-circle text-emerald-500',
                error: 'fa-exclamation-circle text-red-500',
                warning: 'fa-exclamation-triangle text-amber-500',
                info: 'fa-info-circle text-blue-500'
            };

            toast.innerHTML = `
                <i class="fas ${icons[type]} text-xl"></i>
                <span class="flex-1 font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition-colors" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(toast);

            // Auto remove after duration
            setTimeout(() => {
                toast.style.animation = 'slideInFromRight 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // Display Laravel Flash Messages
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
            @if(session('warning'))
                showToast("{{ session('warning') }}", 'warning');
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast("{{ $error }}", 'error', 5000);
                @endforeach
            @endif
        });

        // Modal Management
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Focus trap
            const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        closeModal(modal.id);
                    }
                });
            }
        });

        // Keyboard Navigation
        document.addEventListener('keydown', (e) => {
            // Alt + S: Toggle Sidebar
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                toggleSidebar();
            }

            // Alt + T: Toggle Theme
            if (e.altKey && e.key === 't') {
                e.preventDefault();
                toggleTheme();
            }
        });

        // Navbar Scroll Fade Effect
        let lastScrollTop = 0;
        let scrollThreshold = 50;
        const dashboardNavbar = document.getElementById('dashboard-navbar');

        window.addEventListener('scroll', function () {
            // Don't apply scroll fade if sidebar is open on mobile
            if (window.innerWidth < 768 && sidebar.classList.contains('open')) {
                return;
            }

            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Only trigger fade effect after scrolling past threshold
            if (currentScroll > scrollThreshold) {
                if (currentScroll > lastScrollTop) {
                    // Scrolling DOWN - hide navbar
                    if (dashboardNavbar) {
                        dashboardNavbar.classList.add('navbar-hidden');
                    }
                } else {
                    // Scrolling UP - show navbar
                    if (dashboardNavbar) {
                        dashboardNavbar.classList.remove('navbar-hidden');
                    }
                }
            } else {
                // Near top of page - always show
                if (dashboardNavbar) {
                    dashboardNavbar.classList.remove('navbar-hidden');
                }
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }, false);
    </script>

    <!-- AJAX Navigation System -->
    <script>
        // AJAX Navigation System
        document.addEventListener('DOMContentLoaded', function () {
            // Intercept all navigation link clicks with data-ajax="true"
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a[data-ajax="true"]');

                if (link && !link.target) {
                    e.preventDefault();
                    loadContentViaAjax(link.href);
                }
            });

            // Load content via AJAX
            function loadContentViaAjax(url) {
                const contentArea = document.getElementById('ajax-content');
                if (!contentArea) return;

                // Show loading state
                contentArea.style.opacity = '0.5';
                contentArea.style.pointerEvents = 'none';

                // Add query param to indicate AJAX request
                const ajaxUrl = new URL(url);
                ajaxUrl.searchParams.set('ajax', '1');

                fetch(ajaxUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.text();
                    })
                    .then(html => {
                        // Extract only the content (strip header/sidebar/footer)
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;

                        // Find the main content div - look for content after the header/sidebar
                        let contentToInsert = '';

                        // Try to extract body content excluding header/sidebar/footer
                        const body = tempDiv.querySelector('body');
                        if (body) {
                            // Get all divs and find the one with the most content
                            const allDivs = Array.from(body.querySelectorAll('div'));
                            // Find large content divs (excluding tiny utility divs)
                            const contentDivs = allDivs.filter(div => {
                                const text = div.textContent.trim();
                                return text.length > 100 && !div.querySelector('aside') && !div.querySelector('nav');
                            });

                            if (contentDivs.length > 0) {
                                // Take the largest one
                                const largestDiv = contentDivs.reduce((prev, current) =>
                                    current.textContent.length > prev.textContent.length ? current : prev
                                );
                                contentToInsert = largestDiv.innerHTML;
                            }
                        }

                        // Fallback: if nothing found, try to extract everything except common layout elements
                        if (!contentToInsert) {
                            contentToInsert = html
                                .replace(/<aside[\s\S]*?<\/aside>/gi, '')
                                .replace(/<nav[\s\S]*?<\/nav>/gi, '')
                                .replace(/<header[\s\S]*?<\/header>/gi, '')
                                .replace(/<footer[\s\S]*?<\/footer>/gi, '');
                        }

                        // Update content
                        contentArea.innerHTML = contentToInsert;
                        contentArea.style.opacity = '1';
                        contentArea.style.pointerEvents = 'auto';

                        // Update URL
                        window.history.pushState({ url: url }, '', url);

                        // Update active menu item
                        updateActiveMenu(url);

                        // Scroll to top
                        window.scrollTo(0, 0);

                        // Trigger any necessary re-initialization of scripts
                        if (window.reinitializePageScripts) {
                            window.reinitializePageScripts();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading content:', error);
                        contentArea.style.opacity = '1';
                        contentArea.style.pointerEvents = 'auto';
                        contentArea.innerHTML = '<div class="p-6 bg-red-50 border border-red-200 rounded-lg"><p class="text-red-600">Error loading content. Please try again.</p></div>';
                    });
            }

            // Update active menu highlighting
            function updateActiveMenu(url) {
                // Remove active class from all menu items
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                    item.removeAttribute('aria-current');
                });

                // Find and highlight the matching link
                document.querySelectorAll('a[data-ajax="true"]').forEach(link => {
                    if (link.href === url) {
                        link.classList.add('active');
                        link.setAttribute('aria-current', 'page');

                        // Auto-expand parent submenu if necessary
                        const submenu = link.closest('[id$="Submenu"]');
                        if (submenu && submenu.classList.contains('hidden')) {
                            submenu.classList.remove('hidden');
                            const icon = submenu.previousElementSibling?.querySelector('.fa-chevron-down');
                            if (icon) icon.style.transform = 'rotate(180deg)';
                        }
                    }
                });
            }

            // Handle browser back/forward
            window.addEventListener('popstate', function (e) {
                if (e.state && e.state.url) {
                    loadContentViaAjax(e.state.url, true);
                }
            });
        });
    </script>

    {{-- Toast Container for "Push" Notifications --}}
    <div id="toast-container" class="fixed bottom-20 md:bottom-6 right-6 z-[200] space-y-3 pointer-events-none"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let lastNotificationId = null;
            let initialLoad = true;

            function checkNotifications() {
                fetch('{{ route('api.notifications.unread') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Update bell badge counters (add data-notification-badge to bell icons)
                        document.querySelectorAll('[data-notification-badge]').forEach(badge => {
                            if (data.unread_count > 0) {
                                badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                                badge.classList.remove('hidden');
                            } else {
                                badge.classList.add('hidden');
                            }
                        });

                        if (data.unread_count > 0 && data.latest) {
                            if (lastNotificationId !== data.latest.id) {
                                lastNotificationId = data.latest.id;

                                // Only show toast for NEW notifications after initial page load
                                if (!initialLoad) {
                                    const n = data.latest.data;
                                    showNotificationToast(
                                        n.message || 'You have a new notification.',
                                        n.type    || 'info',
                                        n.icon    || 'fas fa-bell'
                                    );
                                }
                            }
                        }
                        initialLoad = false;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            function showNotificationToast(message, type = 'info', iconClass = 'fas fa-bell') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toast = document.createElement('div');

                // Contextual accent per notification type
                const colorMap = {
                    'new_user_registration': ['bg-blue-50',    'text-blue-500',    'border-blue-100'   ],
                    'new_program_available': ['bg-emerald-50', 'text-emerald-500', 'border-emerald-100' ],
                    'blog_published':        ['bg-purple-50',  'text-purple-500',  'border-purple-100'  ],
                    'new_blog_post':         ['bg-indigo-50',  'text-indigo-500',  'border-indigo-100'  ],
                    'birthday':              ['bg-pink-50',    'text-pink-500',    'border-pink-100'    ],
                    'report':               ['bg-amber-50',   'text-amber-500',   'border-amber-100'   ],
                    'program_update':        ['bg-teal-50',    'text-teal-500',    'border-teal-100'    ],
                };

                const [bg, ic, border] = colorMap[type] || ['bg-slate-50', 'text-[#0B4D73]', 'border-slate-100'];

                toast.className = `pointer-events-auto bg-white/95 backdrop-blur-md border ${border} rounded-2xl shadow-2xl p-4 flex items-start gap-3 animate-fade-in transition-all duration-300 min-w-[290px] max-w-sm`;

                toast.innerHTML = `
                    <div class="w-10 h-10 rounded-xl ${bg} flex items-center justify-center shrink-0 mt-0.5">
                        <i class="${iconClass} ${ic} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0 pt-0.5">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">New Notification</p>
                        <p class="text-sm font-bold text-slate-900 leading-snug line-clamp-2">${message}</p>
                    </div>
                    <button class="text-slate-300 hover:text-slate-500 transition-colors shrink-0 mt-0.5" aria-label="Dismiss">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;

                toast.querySelector('button').onclick = () => {
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                };

                container.appendChild(toast);

                // Auto-dismiss after 8 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 8000);
            }

            // Poll every 15 seconds
            setInterval(checkNotifications, 15000);
            // Run immediately
            checkNotifications();
        });
    </script>

    @yield('scripts')

    {{-- Campaign Modal for Parents & Children --}}
    @if(auth()->check() && (auth()->user()->hasRole('Parent') || auth()->user()->hasRole('Child')))
        @include('partials.campaign-modal')
    @endif

    {{-- PWA Install Prompt --}}
    @include('partials.pwa-install-prompt')
</body>

</html>