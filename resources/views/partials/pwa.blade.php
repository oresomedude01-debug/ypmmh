{{-- ==========================================
PWA Meta Tags & Service Worker Registration
Include this partial in all layout <head> sections
========================================== --}}

{{-- Inject APP_VERSION for version checking --}}
<script>
    window.APP_VERSION = "{{ config('app.version') }}";
    window.APP_NAME = "{{ config('app.name') }}";
    console.log(`[PWA] App Version: ${window.APP_VERSION}`);
</script>

{{-- Web App Manifest --}}
<link rel="manifest" href="/manifest.json" crossorigin="use-credentials">

{{-- Theme Color --}}
<meta name="theme-color" content="#0B4D73">

{{-- iOS / Apple PWA Meta Tags --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ app_name() }}">

{{-- iOS Touch Icons --}}
<link rel="apple-touch-icon" href="/icons/icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">

{{-- Windows / MS Tile Meta --}}
<meta name="msapplication-TileImage" content="/icons/icon-144x144.png">
<meta name="msapplication-TileColor" content="#0B4D73">

{{-- PWA Splash Screen Images for iOS (optional enhancement) --}}
<meta name="apple-mobile-web-app-capable" content="yes">

{{-- Service Worker Registration & Version Check --}}
<script>
    /**
     * PWA Service Worker Registration and Update Handling
     * Handles SW registration with automatic update checking
     */
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker
                .register('/sw.js?v=' + window.APP_VERSION, {
                    scope: '/',
                })
                .then(function (registration) {
                    console.log('[PWA] Service Worker registered:', registration.scope);

                    // Check for updates periodically (every 60 minutes)
                    setInterval(function () {
                        console.log('[PWA] Checking for updates...');
                        registration.update().catch(() => {
                            // Silent fail - online check may fail
                        });
                    }, 60 * 60 * 1000);

                    // Handle when a new SW is installed
                    registration.addEventListener('updatefound', function () {
                        const newWorker = registration.installing;

                        newWorker.addEventListener('statechange', function () {
                            if (
                                newWorker.state === 'installed' &&
                                navigator.serviceWorker.controller
                            ) {
                                // New version available — inform version manager
                                console.log('[PWA] New service worker version available');
                                if (window.__PWA_VERSION_MANAGER__) {
                                    window.__PWA_VERSION_MANAGER__.handleServiceWorkerUpdate(
                                        window.APP_VERSION
                                    );
                                }
                            }
                        });
                    });
                })
                .catch(function (error) {
                    console.error('[PWA] Service Worker registration failed:', error);
                });

            // Listen for messages from SW
            navigator.serviceWorker.addEventListener('message', function (event) {
                if (event.data && event.data.type === 'SW_UPDATED') {
                    console.log('[PWA] Service Worker update message received:', event.data.version);
                }
            });
        });
    }

    /**
     * Fallback: Show a non-intrusive update notification
     * Kept for backward compatibility
     */
    function showUpdateNotification(worker) {
        if (typeof showToast === 'function') {
            showToast('A new version is available! Refreshing...', 'info', 5000);
            setTimeout(function () {
                if (worker?.postMessage) {
                    worker.postMessage({ type: 'SKIP_WAITING' });
                }
                window.location.reload();
            }, 2000);
            return;
        }

        const banner = document.createElement('div');
        banner.id = 'pwa-update-banner';
        banner.innerHTML = `
            <div style="
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: #0B4D73;
                color: white;
                padding: 12px 24px;
                border-radius: 12px;
                font-family: 'Inter', sans-serif;
                font-size: 14px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 12px;
                box-shadow: 0 10px 40px rgba(11, 77, 115, 0.4);
                z-index: 99999;
                animation: slideUp 0.3s ease-out;
            ">
                <span>✨ Update available!</span>
                <button onclick="applyPWAUpdate()" style="
                    background: rgba(255,255,255,0.2);
                    border: none;
                    color: white;
                    padding: 6px 16px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 700;
                    font-family: inherit;
                    font-size: 13px;
                ">Refresh</button>
                <button onclick="this.closest('#pwa-update-banner').remove()" style="
                    background: none;
                    border: none;
                    color: rgba(255,255,255,0.6);
                    cursor: pointer;
                    font-size: 18px;
                    padding: 0 4px;
                ">×</button>
            </div>
            <style>
                @keyframes slideUp {
                    from { opacity: 0; transform: translateX(-50%) translateY(20px); }
                    to { opacity: 1; transform: translateX(-50%) translateY(0); }
                }
            </style>
        `;
            document.body.appendChild(banner);

            window.applyPWAUpdate = function () {
                worker.postMessage({ type: 'SKIP_WAITING' });
                window.location.reload();
            };
        }
    </script>