@extends('layouts.dashboard')

@section('title', 'Community: ' . $program->name)

@section('styles')
    <style>
        /* CRITICAL: Force the main dashboard layout to yield all space to the chat */
        #sidebar,
        #mobile-bottom-nav,
        footer,
        .sidebar,
        nav[role="navigation"] {
            display: none !important;
        }

        /* Ensure the body and html are locked */
        body,
        html {
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* Reset dashboard body/main constraints */
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            min-height: 100vh !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100vw !important;
        }

        #ajax-content {
            flex: 1 1 auto !important;
            padding: 0 !important;
            margin: 0 !important;
            height: 100% !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            min-height: auto !important;
        }

        .fullscreen-chat-wrapper {
            flex: 1 1 auto !important;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            background: #ffffff;
            overflow: hidden !important;
        }

        .fullscreen-chat-header {
            flex: 0 0 70px;
            background: white;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 50;
        }

        .fullscreen-chat-container {
            flex: 1 1 auto;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            background: white;
        }

        @media (max-width: 768px) {
            .fullscreen-chat-header {
                height: 60px;
                padding: 0 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="fullscreen-chat-wrapper">
        <!-- Unified App Header -->
        <header class="fullscreen-chat-header">
            @php
                $backRoute = route('admin.communities.index');
                if (auth()->user()->hasRole('Mentor'))
                    $backRoute = route('mentor.communities.index');
                if (auth()->user()->hasRole('Child'))
                    $backRoute = route('child.programs.show', $program->id);
            @endphp
            <a href="{{ $backRoute }}"
                class="w-10 h-10 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-colors mr-2">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="flex-grow flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0B4D73] text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-comments text-base"></i>
                </div>
                <div>
                    <h1
                        class="font-bold text-slate-900 leading-none mb-1 text-sm md:text-base truncate max-w-[150px] md:max-w-none">
                        {{ $program->name }}
                    </h1>
                    <p class="text-[9px] text-[#0B4D73] font-black uppercase tracking-widest opacity-70">Community</p>
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <!-- Members Button (Moved here for full screen) -->
                <button onclick="document.getElementById('membersModal').classList.remove('hidden')"
                    class="p-2.5 md:px-4 md:py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fas fa-users text-[#0B4D73] md:mr-2"></i>
                    <span class="hidden md:inline">Members</span>
                </button>

                <div id="polling-indicator"
                    class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black tracking-widest uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    LIVE
                </div>
            </div>
        </header>

        <!-- Main Chat Body -->
        <main class="fullscreen-chat-container">
            @include('partials.chat', ['program' => $program, 'fullScreen' => true])
        </main>
    </div>
@endsection

@section('scripts')
    <script>
        function adjustChatLayout() {
            const container = document.querySelector('.chat-container');
            if (container) {
                container.style.height = '100%';
                container.style.flex = '1 1 auto';
                container.style.maxHeight = 'none';
                container.style.minHeight = 'auto';
                container.style.border = 'none';
                container.style.borderRadius = '0';
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.boxShadow = 'none';
            }
        }

        // Initialize immediately and on events
        adjustChatLayout();
        document.addEventListener('DOMContentLoaded', adjustChatLayout);

        // Custom reinitialization for AJAX navigation
        window.reinitializePageScripts = function () {
            // Re-run layout adjustment
            adjustChatLayout();

            // Re-run chat messages fetch
            if (typeof fetchMessages === 'function') {
                fetchMessages();
            }
        };
    </script>
@endsection