<!-- Mobile Bottom Navigation (≤768px only) -->
<nav id="mobile-bottom-nav" class="mobile-bottom-nav glass fixed bottom-0 left-0 right-0 z-[1001] md:hidden"
    style="background-color: var(--glass-bg); border-top: 1px solid var(--border-color); backdrop-filter: blur(20px);"
    role="navigation" aria-label="Mobile navigation">
    <div class="flex items-center justify-around px-2 py-3 safe-area-bottom">
        <!-- Dashboard -->
        @php
            $dashboardRoute = 'dashboard';
            $user = auth()->user();
            if ($user->hasRole('Admin'))
                $dashboardRoute = 'admin.dashboard';
            elseif ($user->hasRole('Mentor'))
                $dashboardRoute = 'mentor.dashboard';
            elseif ($user->hasRole('Parent'))
                $dashboardRoute = 'parent.dashboard';
            elseif ($user->hasRole('Child'))
                $dashboardRoute = 'child.dashboard';
        @endphp
        @if(!$user->hasRole('Parent') && !$user->hasRole('Child'))
            <a href="{{ route($dashboardRoute) }}"
                class="mobile-nav-item {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'page' : 'false' }}">
                <div class="mobile-nav-icon">
                    <i
                        class="fas fa-home text-xl {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'shadow-lg' : '' }}"></i>
                </div>
                <span class="mobile-nav-label">Home</span>
            </a>
        @endif

        @role('Admin')
        <!-- Children (Admin) -->
        <a href="{{ route('admin.children.index') }}"
            class="mobile-nav-item {{ request()->routeIs('admin.children.*') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('admin.children.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon">
                <i
                    class="fas fa-user-graduate text-xl {{ request()->routeIs('admin.children.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Mentees</span>
        </a>

        <!-- Programmes (Admin - Center Action) -->
        <a href="{{ route('admin.programs.index') }}"
            class="mobile-nav-item {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}" aria-label="Programmes"
            aria-current="{{ request()->routeIs('admin.programs.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon ">
                <i
                    class="fas fa-book-open text-2xl {{ request()->routeIs('admin.programs.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Programmes</span>
        </a>

        <!-- Notifications (Admin) -->
        <a href="{{ route('admin.notifications.index') }}"
            class="mobile-nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('admin.notifications.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon relative">
                <i class="fas fa-bell text-xl {{ request()->routeIs('admin.notifications.*') ? 'shadow-lg' : '' }}"></i>
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2"
                        style="border-color: var(--bg-primary);"></span>
                @endif
            </div>
            <span class="mobile-nav-label">Alerts</span>
        </a>
        @endrole

        @role('Mentor')
        <!-- My Programmes (Mentor) -->
        <a href="{{ route('mentor.programs.index') }}"
            class="mobile-nav-item {{ request()->routeIs('mentor.programs.*') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('mentor.programs.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon">
                <i
                    class="fas fa-book-open text-xl {{ request()->routeIs('mentor.programs.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Programmes</span>
        </a>

        <!-- Community Hub (Mentor - Center Action) -->
        <a href="{{ route('mentor.communities.index') }}"
            class="mobile-nav-item {{ request()->routeIs('mentor.communities.*') ? 'active' : '' }}"
            aria-label="Community Hub"
            aria-current="{{ request()->routeIs('mentor.communities.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon ">
                <i
                    class="fas fa-comments text-2xl {{ request()->routeIs('mentor.communities.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Community</span>
        </a>

        <!-- Notifications (Mentor) -->
        <a href="{{ route('mentor.notifications.index') }}"
            class="mobile-nav-item {{ request()->routeIs('mentor.notifications.*') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('mentor.notifications.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon relative">
                <i
                    class="fas fa-bell text-xl {{ request()->routeIs('mentor.notifications.*') ? 'shadow-lg' : '' }}"></i>
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2"
                        style="border-color: var(--bg-primary);"></span>
                @endif
            </div>
            <span class="mobile-nav-label">Alerts</span>
        </a>
        @endrole

        @role('Parent')
        <!-- Parent Dashboard (Center Action) -->
        <a href="{{ route('parent.dashboard') }}"
            class="mobile-nav-item {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}" aria-label="Parent Hub"
            aria-current="{{ request()->routeIs('parent.dashboard') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon mobile-nav-icon-center shadow-lg">
                <i class="fas fa-children text-2xl" style="color: white;"></i>
            </div>
            <span class="mobile-nav-label">Family Hub</span>
        </a>

        <!-- My Children (Parent) -->
        <a href="{{ route('parent.observations') }}"
            class="mobile-nav-item {{ request()->routeIs('parent.observations') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('parent.observations') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-heart text-xl {{ request()->routeIs('parent.observations') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Insights</span>
        </a>

        <!-- Notifications (Parent) -->
        <a href="{{ route('parent.notifications') }}"
            class="mobile-nav-item {{ request()->routeIs('parent.notifications') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('parent.notifications') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon relative">
                <i class="fas fa-bell text-xl {{ request()->routeIs('parent.notifications') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Alerts</span>
        </a>
        @endrole

        @role('Child')
        <!-- My Vault (Child) -->
        <a href="{{ route('child.achievements') }}"
            class="mobile-nav-item {{ request()->routeIs('child.achievements.*') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('child.achievements.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon">
                <i
                    class="fas fa-trophy text-xl {{ request()->routeIs('child.achievements.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">The Vault</span>
        </a>

        <!-- My Journey (Child - Center Action) -->
        <a href="{{ route('child.dashboard') }}"
            class="mobile-nav-item {{ request()->routeIs('child.dashboard') || request()->routeIs('child.programs.*') ? 'active' : '' }}"
            aria-label="My Journey" aria-current="{{ request()->routeIs('child.dashboard') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon ">
                <i
                    class="fas fa-map-marked-alt text-2xl {{ request()->routeIs('child.dashboard') || request()->routeIs('child.programs.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Journey</span>
        </a>

        <!-- Events (Child) -->
        <a href="{{ route('child.events.index') }}"
            class="mobile-nav-item {{ request()->routeIs('child.events.*') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('child.events.*') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon">
                <i
                    class="fas fa-calendar-alt text-xl {{ request()->routeIs('child.events.*') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Events</span>
        </a>

        <!-- Explore (Child) -->
        <a href="{{ route('parent.programs.catalog') }}"
            class="mobile-nav-item {{ request()->routeIs('parent.programs.catalog') ? 'active' : '' }}"
            aria-current="{{ request()->routeIs('parent.programs.catalog') ? 'page' : 'false' }}">
            <div class="mobile-nav-icon">
                <i
                    class="fas fa-compass text-xl {{ request()->routeIs('parent.programs.catalog') ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">New Programs</span>
        </a>
        @endrole

        <!-- More/Menu -->
        @php
            $isOtherActive = request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ||
                request()->routeIs('admin.children.*') || request()->routeIs('admin.programs.*') || request()->routeIs('admin.notifications.*') ||
                request()->routeIs('mentor.programs.*') || request()->routeIs('mentor.communities.*') || request()->routeIs('mentor.notifications.*') ||
                request()->routeIs('parent.dashboard') || request()->routeIs('parent.observations') || request()->routeIs('parent.notifications') ||
                request()->routeIs('child.achievements.*') || request()->routeIs('child.dashboard') || request()->routeIs('child.profile') || request()->routeIs('child.programs.*') || request()->routeIs('child.events.*');
        @endphp
        <button onclick="toggleSidebar()" class="mobile-nav-item {{ !$isOtherActive ? 'active' : '' }}"
            aria-label="Open menu" aria-expanded="false" aria-controls="sidebar">
            <div class="mobile-nav-icon">
                <i class="fas fa-bars text-xl {{ !$isOtherActive ? 'shadow-lg' : '' }}"></i>
            </div>
            <span class="mobile-nav-label">Menu</span>
        </button>
    </div>
</nav>


<style>
    /* Mobile Bottom Navigation Styles */
    .mobile-bottom-nav {
        box-shadow: 0 -4px 24px 0 var(--shadow-color);
        padding-bottom: env(safe-area-inset-bottom, 0px);
    }

    /* Safe area for devices with notches/home indicators */
    .safe-area-bottom {
        padding-bottom: env(safe-area-inset-bottom, 0.75rem);
    }

    /* Mobile Nav Item */
    .mobile-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 0.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: var(--text-secondary);
        min-width: 60px;
        position: relative;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    /* Mobile Nav Icon Container */
    .mobile-nav-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: transparent;
    }

    /* Center Action Icon (Elevated) */
    .mobile-nav-icon-center {
        width: 52px;
        height: 52px;
        background-color: var(--primary-500);
        margin-top: -12px;
        border-radius: 16px;
        box-shadow: 0 8px 16px -4px var(--primary-500);
    }

    /* Mobile Nav Label */
    .mobile-nav-label {
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: color 0.2s ease;
    }

    /* Active State */
    .mobile-nav-item.active {
        color: var(--primary-500);
    }

    .mobile-nav-item.active .mobile-nav-icon:not(.mobile-nav-icon-center) {
        background-color: var(--primary-500);
        color: white;
    }

    .mobile-nav-item.active .mobile-nav-label {
        color: var(--primary-500);
    }

    /* Hover/Touch State */
    .mobile-nav-item:active {
        transform: scale(0.92);
    }

    /* Hide on tablet and desktop */
    @media (min-width: 769px) {
        .mobile-bottom-nav {
            display: none;
        }
    }

    /* Add space for bottom nav on mobile */
    @media (max-width: 768px) {
        .main-content {
            padding-bottom: 90px !important;
        }
    }
</style>