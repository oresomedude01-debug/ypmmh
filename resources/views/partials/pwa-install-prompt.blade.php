{{-- ============================================
PWA Smart Install Prompt
============================================
- Detects if app is NOT installed (standalone)
- Captures beforeinstallprompt for Chrome/Edge/Android
- Shows manual instructions for iOS Safari
- Frequency: once every 7 days, never after install
- Place this partial just before </body> in layouts
============================================ --}}

{{-- Install Prompt Modal --}}
<div id="pwa-install-modal" style="display:none;" aria-hidden="true" role="dialog" aria-labelledby="pwa-install-title"
    aria-describedby="pwa-install-desc">
    {{-- Backdrop --}}
    <div id="pwa-install-backdrop" style="
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 99998;
        opacity: 0;
        transition: opacity 0.3s ease;
    "></div>

    {{-- Modal Card --}}
    <div id="pwa-install-card" style="
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99999;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1);
    ">
        <div style="
            max-width: 480px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-bottom: none;
            border-radius: 24px 24px 0 0;
            padding: 0;
            box-shadow: 0 -20px 60px rgba(11, 77, 115, 0.15);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow: hidden;
        ">
            {{-- Drag Handle --}}
            <div style="display:flex; justify-content:center; padding: 12px 0 4px;">
                <div style="width:36px; height:4px; background:#cbd5e1; border-radius:2px;"></div>
            </div>

            {{-- App Icon + Header --}}
            <div style="padding: 8px 24px 0; text-align: center;">
                <div style="
                    width: 64px;
                    height: 64px;
                    margin: 0 auto 14px;
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 4px 16px rgba(11, 77, 115, 0.2);
                ">
                    <img src="{{ asset('icons/icon-192x192.png') }}" alt="{{ app_name() }}"
                        style="width:100%; height:100%; object-fit:cover;">
                </div>
                <h3 id="pwa-install-title" style="
                    font-size: 1.2rem;
                    font-weight: 800;
                    color: #0f172a;
                    margin: 0 0 6px;
                    letter-spacing: -0.02em;
                ">Install {{ app_name() }}</h3>
                <p id="pwa-install-desc" style="
                    font-size: 0.82rem;
                    color: #64748b;
                    margin: 0 0 20px;
                    line-height: 1.5;
                ">Add to your home screen for the best experience</p>
            </div>

            {{-- Benefits --}}
            <div style="padding: 0 24px 20px; display: flex; flex-direction: column; gap: 10px;">
                <div
                    style="display:flex; align-items:center; gap:12px; padding:10px 14px; background:rgba(11,77,115,0.04); border-radius:12px;">
                    <div
                        style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#0B4D73,#0ea5e9); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:0.8rem; font-weight:700; color:#0f172a;">Lightning Fast</div>
                        <div style="font-size:0.72rem; color:#94a3b8; line-height:1.3;">Launch instantly from your home
                            screen</div>
                    </div>
                </div>
                <div
                    style="display:flex; align-items:center; gap:12px; padding:10px 14px; background:rgba(11,77,115,0.04); border-radius:12px;">
                    <div
                        style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#0B4D73,#0ea5e9); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 20h.01"></path>
                            <path d="M7 20v-4"></path>
                            <path d="M12 20v-8"></path>
                            <path d="M17 20V8"></path>
                            <path d="M22 4v16"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:0.8rem; font-weight:700; color:#0f172a;">Works Offline</div>
                        <div style="font-size:0.72rem; color:#94a3b8; line-height:1.3;">Access content even without
                            internet</div>
                    </div>
                </div>
                <div
                    style="display:flex; align-items:center; gap:12px; padding:10px 14px; background:rgba(11,77,115,0.04); border-radius:12px;">
                    <div
                        style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#0B4D73,#0ea5e9); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:0.8rem; font-weight:700; color:#0f172a;">Stay Updated</div>
                        <div style="font-size:0.72rem; color:#94a3b8; line-height:1.3;">Get notifications for new
                            content & events</div>
                    </div>
                </div>
            </div>

            {{-- iOS Safari Instructions (hidden by default) --}}
            <div id="pwa-ios-instructions" style="display:none; padding: 0 24px 20px;">
                <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:14px 16px;">
                    <p style="font-size:0.8rem; font-weight:700; color:#92400e; margin:0 0 10px;">How to install on
                        iPhone / iPad:</p>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span
                                style="width:24px; height:24px; border-radius:7px; background:#0B4D73; color:white; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:800; flex-shrink:0;">1</span>
                            <span style="font-size:0.78rem; color:#78350f;">Tap the <strong>Share</strong> button <svg
                                    style="display:inline; vertical-align:middle;" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="#0B4D73" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                    <polyline points="16 6 12 2 8 6"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="15"></line>
                                </svg> in the toolbar</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span
                                style="width:24px; height:24px; border-radius:7px; background:#0B4D73; color:white; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:800; flex-shrink:0;">2</span>
                            <span style="font-size:0.78rem; color:#78350f;">Scroll down and tap <strong>"Add to Home
                                    Screen"</strong></span>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span
                                style="width:24px; height:24px; border-radius:7px; background:#0B4D73; color:white; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:800; flex-shrink:0;">3</span>
                            <span style="font-size:0.78rem; color:#78350f;">Tap <strong>"Add"</strong> to install</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="padding: 0 24px 24px; display:flex; flex-direction:column; gap:10px;">
                <button id="pwa-install-btn" onclick="PWAInstallPrompt.install()" style="
                    width: 100%;
                    padding: 14px 24px;
                    background: linear-gradient(135deg, #0B4D73 0%, #0c5e8a 100%);
                    color: white;
                    border: none;
                    border-radius: 14px;
                    font-family: inherit;
                    font-size: 0.9rem;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                    box-shadow: 0 4px 14px rgba(11, 77, 115, 0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                ">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Install App
                </button>
                <button id="pwa-install-later-btn" onclick="PWAInstallPrompt.dismiss()" style="
                    width: 100%;
                    padding: 12px 24px;
                    background: transparent;
                    color: #64748b;
                    border: none;
                    border-radius: 14px;
                    font-family: inherit;
                    font-size: 0.82rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                ">
                    Maybe Later
                </button>
            </div>

            {{-- Safe area bottom padding for iOS --}}
            <div style="height: env(safe-area-inset-bottom, 0px);"></div>
        </div>
    </div>
</div>

{{-- Install Prompt + Button Controller JavaScript --}}
<script>
    /**
     * PWA Smart Install Prompt Controller
     * ====================================
     * Handles install prompt logic across all browsers.
     * Exposes API for both the modal and header/hero buttons.
     */
    const PWAInstallPrompt = (function () {
        'use strict';

        const CONFIG = {
            STORAGE_KEY: 'pwa_install_prompt',
            COOLDOWN_DAYS: 7,
            SHOW_DELAY_MS: 4000,
            DISMISS_LIMIT: 3,
        };

        let deferredPrompt = null;
        let isIOS = false;
        let isStandalone = false;
        let modalVisible = false;

        function detectStandalone() {
            if (window.matchMedia('(display-mode: standalone)').matches) return true;
            if (window.navigator.standalone === true) return true;
            if (window.matchMedia('(display-mode: fullscreen)').matches) return true;
            return false;
        }

        function detectIOS() {
            const ua = window.navigator.userAgent;
            const isIOSDevice = /iPad|iPhone|iPod/.test(ua) ||
                (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            return isIOSDevice && !/CriOS/.test(ua) && !/FxiOS/.test(ua);
        }

        function getStorageData() {
            try {
                const raw = localStorage.getItem(CONFIG.STORAGE_KEY);
                return raw ? JSON.parse(raw) : {};
            } catch (e) { return {}; }
        }

        function setStorageData(data) {
            try {
                localStorage.setItem(CONFIG.STORAGE_KEY, JSON.stringify({ ...getStorageData(), ...data }));
            } catch (e) { }
        }

        function shouldShowPrompt() {
            const d = getStorageData();
            if (d.installed) return false;
            if ((d.dismissCount || 0) >= CONFIG.DISMISS_LIMIT) return false;
            if (d.lastDismissed) {
                if ((Date.now() - d.lastDismissed) / 864e5 < CONFIG.COOLDOWN_DAYS) return false;
            }
            return true;
        }

        // ── Modal ──────────────────────────────────

        function showModal() {
            const modal = document.getElementById('pwa-install-modal');
            const backdrop = document.getElementById('pwa-install-backdrop');
            const card = document.getElementById('pwa-install-card');
            const installBtn = document.getElementById('pwa-install-btn');
            if (!modal) return;

            if (isIOS) {
                const ios = document.getElementById('pwa-ios-instructions');
                if (ios) ios.style.display = 'block';
                if (installBtn) {
                    installBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg> Got It!';
                    installBtn.onclick = function () { PWAInstallPrompt.dismiss(); };
                }
            }

            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    backdrop.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            });
            backdrop.onclick = function () { PWAInstallPrompt.dismiss(); };
            document.addEventListener('keydown', handleEsc);
            modalVisible = true;
        }

        function hideModal(cb) {
            const modal = document.getElementById('pwa-install-modal');
            const backdrop = document.getElementById('pwa-install-backdrop');
            const card = document.getElementById('pwa-install-card');
            if (!modal) return;
            backdrop.style.opacity = '0';
            card.style.transform = 'translateY(100%)';
            setTimeout(function () {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.removeEventListener('keydown', handleEsc);
                modalVisible = false;
                if (cb) cb();
            }, 400);
        }

        function handleEsc(e) {
            if (e.key === 'Escape' && modalVisible) PWAInstallPrompt.dismiss();
        }

        // ── Init ───────────────────────────────────

        function init() {
            isStandalone = detectStandalone();
            isIOS = detectIOS();

            if (isStandalone) {
                setStorageData({ installed: true });
                PWAInstallButton._hideAll();
                return;
            }

            // iOS: show install buttons immediately (no beforeinstallprompt)
            if (isIOS) PWAInstallButton._showAll();

            window.addEventListener('appinstalled', function () {
                setStorageData({ installed: true });
                hideModal();
                deferredPrompt = null;
                PWAInstallButton._hideAll();
            });

            window.addEventListener('beforeinstallprompt', function (e) {
                e.preventDefault();
                deferredPrompt = e;
                PWAInstallButton._showAll();
                schedulePrompt();
            });

            if (isIOS && shouldShowPrompt()) schedulePrompt();

            try {
                window.matchMedia('(display-mode: standalone)').addEventListener('change', function (e) {
                    if (e.matches) { setStorageData({ installed: true }); PWAInstallButton._hideAll(); }
                });
            } catch (e) { }
        }

        function schedulePrompt() {
            if (!shouldShowPrompt()) return;
            setTimeout(function () {
                if (!shouldShowPrompt() || detectStandalone()) return;
                showModal();
            }, CONFIG.SHOW_DELAY_MS);
        }

        function install() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function (r) {
                    if (r.outcome === 'accepted') {
                        setStorageData({ installed: true });
                        PWAInstallButton._hideAll();
                    } else {
                        setStorageData({ lastDismissed: Date.now(), dismissCount: (getStorageData().dismissCount || 0) + 1 });
                    }
                    deferredPrompt = null;
                });
                hideModal();
            } else if (isIOS) {
                dismiss();
            }
        }

        function dismiss() {
            setStorageData({ lastDismissed: Date.now(), dismissCount: (getStorageData().dismissCount || 0) + 1 });
            hideModal();
        }

        function setupHoverEffects() {
            var btn = document.getElementById('pwa-install-btn');
            var lat = document.getElementById('pwa-install-later-btn');
            if (btn) {
                btn.onmouseenter = function () { this.style.transform = 'translateY(-2px)'; this.style.boxShadow = '0 8px 25px rgba(11,77,115,0.45)'; };
                btn.onmouseleave = function () { this.style.transform = ''; this.style.boxShadow = ''; };
            }
            if (lat) {
                lat.onmouseenter = function () { this.style.background = 'rgba(241,245,249,0.8)'; };
                lat.onmouseleave = function () { this.style.background = 'transparent'; };
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () { setupHoverEffects(); init(); });
        } else {
            setupHoverEffects(); init();
        }

        return {
            install: install,
            dismiss: dismiss,
            show: showModal,
            hide: hideModal,
            getDeferredPrompt: function () { return deferredPrompt; },
            isIOSDevice: function () { return isIOS; },
            isInstalled: function () { return isStandalone || getStorageData().installed === true; },
        };
    })();

    /**
     * PWA Install Button Controller
     * ====================================
     * Manages all "Download App" / "Get App" buttons across
     * the site (header, mobile menu, hero section).
     */
    const PWAInstallButton = (function () {
        'use strict';

        // IDs of individual install buttons
        const BUTTON_IDS = [
            'pwa-header-install-btn',
            'pwa-mobile-install-btn',
            'pwa-hero-install-btn',
        ];

        /**
         * Show all install buttons + their <li> wrappers in mobile menus
         */
        function showAll() {
            BUTTON_IDS.forEach(function (id) {
                var btn = document.getElementById(id);
                if (btn) {
                    btn.style.display = '';
                    // Desktop header button: show only on md+ screens via Tailwind
                    if (id === 'pwa-header-install-btn') {
                        btn.classList.remove('hidden');
                        btn.classList.add('hidden', 'md:inline-flex');
                    }
                }
            });
            // Also show the <li> wrappers in mobile menus
            document.querySelectorAll('.pwa-mobile-menu-item').forEach(function (li) {
                li.style.display = '';
            });
        }

        /**
         * Hide all install buttons + their <li> wrappers
         */
        function hideAll() {
            BUTTON_IDS.forEach(function (id) {
                var btn = document.getElementById(id);
                if (btn) btn.style.display = 'none';
            });
            document.querySelectorAll('.pwa-mobile-menu-item').forEach(function (li) {
                li.style.display = 'none';
            });
        }

        /**
         * Handle click on any install button
         */
        function handleClick() {
            var deferred = PWAInstallPrompt.getDeferredPrompt();
            if (deferred) {
                deferred.prompt();
                deferred.userChoice.then(function (r) {
                    if (r.outcome === 'accepted') hideAll();
                });
            } else {
                // iOS or fallback — show the modal with instructions
                PWAInstallPrompt.show();
            }
        }

        return {
            handleClick: handleClick,
            _showAll: showAll,
            _hideAll: hideAll,
        };
    })();
</script>