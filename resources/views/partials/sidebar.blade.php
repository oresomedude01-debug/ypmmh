<aside id="sidebar" class="sidebar glass overflow-y-auto">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 flex items-center justify-center">
                <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ app_name() }}</h1>
                <p class="text-xs text-slate-500 font-medium">{{ app_tagline() }}</p>
            </div>
        </div>


        <!-- Close Button (Mobile Only) -->
        <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 p-2 rounded-lg glass-hover"
            aria-label="Close sidebar">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Navigation Menu -->
        <nav role="navigation" aria-label="Main navigation">
            <!-- Dashboard Section -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--text-secondary);">
                    Main Menu
                </h3>

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
                @if(!auth()->user()->hasRole('Child') && !auth()->user()->hasRole('Parent'))
                    <a href="{{ route($dashboardRoute) }}"
                        class="menu-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('mentor.dashboard') || request()->routeIs('parent.dashboard') || request()->routeIs('child.dashboard') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                        <i class="fas fa-home w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                @endif

                @role('Mentor')
                <a href="{{ route('mentor.programs.index') }}"
                    class="menu-item {{ request()->routeIs('mentor.programs.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-book-open w-5"></i>
                    <span class="font-medium">My Programmes</span>
                </a>
                <a href="{{ route('mentor.communities.index') }}"
                    class="menu-item {{ request()->routeIs('mentor.communities.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-comments w-5"></i>
                    <span class="font-medium">Community Hub</span>
                </a>
                <a href="{{ route('mentor.events.index') }}"
                    class="menu-item {{ request()->routeIs('mentor.events.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span class="font-medium">Events</span>
                </a>
                <a href="{{ route('mentor.blogs.index') }}"
                    class="menu-item {{ request()->routeIs('mentor.blogs.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-pen-nib w-5"></i>
                    <span class="font-medium">My Blogs</span>
                </a>
                <a href="{{ route('mentor.notifications.index') }}"
                    class="menu-item {{ request()->routeIs('mentor.notifications.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-bell w-5"></i>
                    <span class="font-medium">Notifications</span>
                </a>
                @endrole

                @role('Child')
                <a href="{{ route('child.dashboard') }}"
                    class="menu-item {{ request()->routeIs('child.dashboard') || request()->routeIs('child.programs.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-map-marked-alt w-5"></i>
                    <span class="font-bold">My Journey</span>
                </a>
                <a href="{{ route('child.profile') }}"
                    class="menu-item {{ request()->routeIs('child.profile') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-id-card w-5 text-blue-500"></i>
                    <span class="font-bold">My Profile Data</span>
                </a>
                <a href="{{ route('child.achievements') }}"
                    class="menu-item {{ request()->routeIs('child.achievements') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-trophy w-5"></i>
                    <span class="font-bold">My Vault</span>
                </a>
                <a href="{{ route('child.communities.index') }}"
                    class="menu-item {{ request()->routeIs('child.communities.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-mosque w-5 text-emerald-600"></i>
                    <span class="font-bold">The Halaqah</span>
                </a>
                <a href="{{ route('child.events.index') }}"
                    class="menu-item {{ request()->routeIs('child.events.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-calendar-alt w-5 text-indigo-500"></i>
                    <span class="font-bold">Events</span>
                </a>
                <a href="{{ route('parent.programs.catalog') }}"
                    class="menu-item {{ request()->routeIs('parent.programs.catalog') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-compass w-5 text-rose-500"></i>
                    <span class="font-bold">New Programmes</span>
                </a>
                <a href="{{ route('premium.subscribe') }}"
                    class="menu-item {{ request()->routeIs('premium.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition-all">
                    <i class="fas fa-crown w-5 text-yellow-500"></i>
                    <span class="font-bold text-yellow-700">Premium Upgrade</span>
                </a>
                <a href="{{ route('child.notifications.index') }}"
                    class="menu-item {{ request()->routeIs('child.notifications.*') ? 'active' : 'glass-hover' }} flex items-center justify-between px-4 py-3 rounded-xl mb-2 transition-all">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-bell w-5"></i>
                        <span class="font-bold">My Alerts</span>
                    </div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span
                            class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-red-500/20">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                @endrole

                @role('Parent')
                <a href="{{ route('parent.dashboard') }}"
                    class="menu-item {{ request()->routeIs('parent.dashboard') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                    <i class="fas fa-home w-5 text-[#0B4D73]"></i>
                    <span class="font-medium">Family Hub</span>
                </a>
                <a href="{{ route('parent.programs.catalog') }}"
                    class="menu-item {{ request()->routeIs('parent.programs.catalog') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                    <i class="fas fa-search w-5 text-blue-500"></i>
                    <span class="font-medium">Program Catalog</span>
                </a>
                <a href="{{ route('premium.subscribe') }}"
                    class="menu-item {{ request()->routeIs('premium.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                    <i class="fas fa-crown w-5 text-yellow-500"></i>
                    <span class="font-bold text-yellow-700">Premium Subscriptions</span>
                </a>
                <a href="{{ route('parent.observations') }}"
                    class="menu-item {{ request()->routeIs('parent.observations') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                    <i class="fas fa-glasses w-5 text-indigo-500"></i>
                    <span class="font-medium">Mentor Insights</span>
                </a>
                <a href="{{ route('parent.events.index') }}"
                    class="menu-item {{ request()->routeIs('parent.events.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                    <i class="fas fa-calendar-alt w-5 text-emerald-500"></i>
                    <span class="font-medium">Events</span>
                </a>
                <a href="{{ route('parent.notifications') }}"
                    class="menu-item {{ request()->routeIs('parent.notifications') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all">
                    <i class="fas fa-bell w-5 text-rose-500"></i>
                    <span class="font-medium">Portal Alerts</span>
                </a>
                <button onclick="openFeedbackModal()"
                    class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2 transition-all glass-hover">
                    <i class="fas fa-comment-alt w-5 text-amber-500"></i>
                    <span class="font-medium">Share Feedback</span>
                </button>
                @endrole

                @role('Admin')
                <div class="mb-2">
                    @php $isUserManagementActive = request()->routeIs('admin.users.*') || request()->routeIs('admin.mentors.*') || request()->routeIs('admin.children.*') || request()->routeIs('admin.parents.*'); @endphp
                    <button onclick="toggleSubmenu('usersSubmenu')"
                        class="menu-item flex items-center justify-between w-full px-4 py-3 rounded-lg {{ $isUserManagementActive ? 'active' : 'glass-hover' }}"
                        aria-expanded="{{ $isUserManagementActive ? 'true' : 'false' }}" aria-controls="usersSubmenu">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users w-5"></i>
                            <span class="font-medium">User Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform {{ $isUserManagementActive ? 'rotate-180' : '' }}"
                            id="usersSubmenuIcon"></i>
                    </button>
                    <div id="usersSubmenu" class="ml-8 mt-2 {{ $isUserManagementActive ? '' : 'hidden' }}">
                        <a href="{{route('admin.users.index')}}"
                            class="block px-4 py-2 rounded-lg mb-1 {{ request()->routeIs('admin.users.index') ? 'active bg-[#0B4D73] text-white' : 'glass-hover text-slate-500' }}">
                            All Users
                        </a>
                        <a href="{{route('admin.mentors.index')}}"
                            class="block px-4 py-2 rounded-lg mb-1 {{ request()->routeIs('admin.mentors.*') ? 'active bg-[#0B4D73] text-white' : 'glass-hover text-slate-500' }}">
                            Mentors
                        </a>
                        <a href="{{route('admin.children.index')}}"
                            class="block px-4 py-2 rounded-lg mb-1 {{ request()->routeIs('admin.children.*') ? 'active bg-[#0B4D73] text-white' : 'glass-hover text-slate-500' }}">
                            Mentees
                        </a>
                        <a href="{{route('admin.parents.index')}}"
                            class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.parents.index') ? 'active bg-[#0B4D73] text-white' : 'glass-hover text-slate-500' }}">
                            Parents/Guardians
                        </a>
                    </div>
                </div>


                <a href="{{route('admin.programs.index')}}"
                    class="menu-item {{ request()->routeIs('admin.programs.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="font-medium">Programmes</span>
                </a>

                <a href="{{route('admin.blogs.index')}}"
                    class="menu-item {{ request()->routeIs('admin.blogs.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-newspaper w-5"></i>
                    <span class="font-medium">Blogs</span>
                </a>

                <a href="{{route('admin.gallery.index')}}"
                    class="menu-item {{ request()->routeIs('admin.gallery.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-images w-5"></i>
                    <span class="font-medium">Gallery</span>
                </a>

                <a href="{{route('admin.events.index')}}"
                    class="menu-item {{ request()->routeIs('admin.events.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span class="font-medium">Events</span>
                </a>

                <a href="{{route('admin.communities.index')}}"
                    class="menu-item {{ request()->routeIs('admin.communities.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-comments w-5"></i>
                    <span class="font-medium">Community Hub</span>
                </a>

                <a href="{{route('admin.notifications.index')}}"
                    class="menu-item {{ request()->routeIs('admin.notifications.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-bell w-5"></i>
                    <span class="font-medium">Notifications</span>
                </a>

                <a href="{{route('admin.reports.index')}}"
                    class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-layer-group w-5"></i>
                    <span class="font-medium">Report & Feedbacks</span>
                </a>

                <a href="{{route('admin.mail.index')}}"
                    class="menu-item {{ request()->routeIs('admin.mail.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-envelope w-5"></i>
                    <span class="font-medium">Send Direct Mail</span>
                </a>
            </div>

            <!-- Finance Section -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--text-secondary);">
                    Finance
                </h3>

                <a href="{{route('admin.payments.index')}}"
                    class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-credit-card w-5"></i>
                    <span class="font-medium">Payments</span></a>
            </div>

            <!-- Settings Section -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--text-secondary);">
                    System
                </h3>

                <a href="{{ route('admin.settings.index') }}"
                    class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : 'glass-hover' }} flex items-center gap-3 px-4 py-3 rounded-lg mb-2">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Settings</span>
                </a>
                @endrole
            </div>
        </nav>

        <!-- Bottom Section (Hidden on Mobile) -->
        <div class="mt-8 pt-6 border-t hidden md:block" style="border-color: var(--border-color);">
            <div class="glass rounded-lg p-4">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fas fa-info-circle" style="color: var(--primary-500);"></i>
                    <span class="font-semibold text-sm">Need Help?</span>
                </div>
                <p class="text-xs mb-3" style="color: var(--text-secondary);">
                    Check our documentation or contact support.
                </p>
                <button class="btn btn-primary w-full text-xs">
                    <i class="fas fa-question-circle"></i>
                    Get Support
                </button>
            </div>
        </div>
    </div>
</aside>

<script>
    // Submenu Toggle
    function toggleSubmenu(submenuId) {
        const submenu = document.getElementById(submenuId);
        const icon = document.getElementById(submenuId + 'Icon');
        const button = icon.closest('button');

        const isHidden = submenu.classList.contains('hidden');

        submenu.classList.toggle('hidden');
        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
    }

</script>