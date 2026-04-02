{{-- ============================================
PWA Install Button (Desktop Header Only)
============================================
Renders a compact "Get App" button for the desktop
navigation. Hidden by default — shown by
PWAInstallButton._showAll() when the app is installable.

Mobile button is placed directly in mobile menus
(welcome.blade.php / public.blade.php) inside the <ul>.
    ============================================ --}}

    {{-- Desktop Header Button --}}
    <button id="pwa-header-install-btn" onclick="PWAInstallButton.handleClick()" style="display: none;" class="pwa-install-header-btn hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 lg:px-4 lg:py-2 rounded-xl text-[10px] lg:text-xs font-bold transition-all duration-300 whitespace-nowrap
           bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md shadow-emerald-500/20
           hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 hover:from-emerald-600 hover:to-teal-600"
        aria-label="Install app">
        <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        <span class="hidden lg:inline">Get App</span>
    </button>