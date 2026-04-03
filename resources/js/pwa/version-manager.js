/**
 * PWA Version Check System
 * ========================
 * Manages application versioning, detects updates, clears stale data,
 * and coordinates service worker updates for seamless PWA deployments.
 */

import {
    clearAllStorage,
    storeAppVersion,
    getStoredAppVersion,
    isVersionChange,
    getReloadCounter,
    resetReloadCounter,
    showUpdateNotification,
    hideUpdateNotification,
    reloadPageWithCacheBusting,
} from './storage-utils.js';

class PWAVersionManager {
    constructor() {
        this.currentVersion = window.APP_VERSION || '1.0.0';
        this.updateInProgress = false;
        this.reloadScheduled = false;
        this.swUpdateController = null;
    }

    /**
     * Initialize version checking on app load
     * Should be called as early as possible in app lifecycle
     * @returns {Promise<void>}
     */
    async init() {
        console.log(`[PWA] Version Manager initializing (current: ${this.currentVersion})`);

        try {
            const storedVersion = getStoredAppVersion();

            // Version changed - perform full update
            if (isVersionChange(this.currentVersion, storedVersion)) {
                await this.handleVersionChange(storedVersion);
            }

            // Setup service worker monitoring
            if ('serviceWorker' in navigator) {
                this.setupServiceWorkerMonitoring();
            }

            // Setup periodic version checks (every 15 minutes in background)
            this.setupPeriodicVersionCheck();
        } catch (error) {
            console.error('[PWA] Version manager init error:', error);
        }
    }

    /**
     * Called when a version change is detected
     * Clears old data and schedules reload
     * @param {string|null} oldVersion
     * @returns {Promise<void>}
     */
    async handleVersionChange(oldVersion) {
        if (this.updateInProgress) {
            console.log('[PWA] Update already in progress');
            return;
        }

        this.updateInProgress = true;

        try {
            console.log(`[PWA] Version change detected: ${oldVersion || 'fresh'} → ${this.currentVersion}`);

            // Show user-friendly message
            showUpdateNotification('Preparing app update...');

            // Step 1: Clear all stale data
            const cleared = await clearAllStorage();

            if (!cleared) {
                console.warn('[PWA] Warning: Some storage could not be cleared');
            }

            // Step 2: Store new version
            storeAppVersion(this.currentVersion);

            // Step 3: Reset reload counter for new version
            resetReloadCounter();

            console.log('[PWA] ✓ Version change processed, reloading...');

            // Step 4: Reload page with cache busting
            this.scheduleReload(1500);
        } catch (error) {
            console.error('[PWA] Error handling version change:', error);
            // Still reload even on error - user will retry
            this.scheduleReload(2000);
        }
    }

    /**
     * Setup service worker monitoring for updates
     * Listens for SW controllerchange and SW messages
     * @returns {void}
     */
    setupServiceWorkerMonitoring() {
        // Listen for service worker becoming the controller (after update)
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            console.log('[PWA] Service worker controller changed - update detected');

            if ('onLine' in navigator && navigator.onLine) {
                // Reload to get latest assets
                this.scheduleReload(1000, 'Service worker updated');
            }
        });

        // Listen for messages from service worker
        navigator.serviceWorker.addEventListener('message', (event) => {
            const { data } = event;

            if (data?.type === 'SW_UPDATED') {
                console.log(`[PWA] Service worker notified update: ${data.version}`);
                this.handleServiceWorkerUpdate(data.version);
            }

            if (data?.type === 'SW_UPDATE_READY') {
                console.log('[PWA] Service worker update ready');
                // Allow graceful update for user
                showUpdateNotification('New version available - refreshing...');
                this.scheduleReload(2000);
            }
        });
    }

    /**
     * Handle service worker update notification
     * @param {string} newVersion
     * @returns {void}
     */
    handleServiceWorkerUpdate(newVersion) {
        if (newVersion !== this.currentVersion) {
            console.log(`[PWA] SW version mismatch: app=${this.currentVersion}, sw=${newVersion}`);
            // Force full version change handling
            this.handleVersionChange(getStoredAppVersion());
        }
    }

    /**
     * Check for updates periodically
     * Calls SW update() to check for new versions
     * @returns {void}
     */
    setupPeriodicVersionCheck() {
        if (!('serviceWorker' in navigator)) return;

        // Check every 15 minutes
        const checkInterval = 15 * 60 * 1000;

        setInterval(async () => {
            try {
                const registration = await navigator.serviceWorker.ready;
                registration.update().catch((error) => {
                    console.error('[PWA] Error checking for updates:', error);
                });
            } catch (error) {
                console.error('[PWA] Periodic update check failed:', error);
            }
        }, checkInterval);

        console.log('[PWA] Periodic version check enabled (15-minute interval)');
    }

    /**
     * Schedule a page reload with safety checks
     * Prevents infinite reload loops
     * @param {number} delay - Delay in ms before reload
     * @param {string} reason - Reason for reload (for logging)
     * @returns {void}
     */
    scheduleReload(delay = 1000, reason = 'Version update') {
        if (this.reloadScheduled) {
            console.log('[PWA] Reload already scheduled');
            return;
        }

        // Check reload counter to prevent infinite loops
        const counter = getReloadCounter();

        if (counter.exceeded) {
            console.error(
                `[PWA] ⚠ Too many reloads (${counter.count}/${counter.limit}) - aborting to prevent loop`
            );
            showUpdateNotification('⚠ Update has issues. Please refresh manually.');
            return;
        }

        this.reloadScheduled = true;

        console.log(
            `[PWA] Reload scheduled in ${delay}ms (${reason}) [${counter.count}/${counter.limit}]`
        );

        setTimeout(() => {
            hideUpdateNotification();
            reloadPageWithCacheBusting();
        }, delay);
    }

    /**
     * Manual version check (for debugging/testing)
     * Can be called from console or UI
     * @returns {Promise<object>}
     */
    async checkVersion() {
        const storedVersion = getStoredAppVersion();
        const hasChanged = isVersionChange(this.currentVersion, storedVersion);

        const info = {
            currentVersion: this.currentVersion,
            storedVersion,
            hasChanged,
            timestamp: new Date().toISOString(),
        };

        console.log('[PWA] Version check:', info);
        return info;
    }

    /**
     * Force clear all data and reload
     * Useful for manual user request to "reset app"
     * @returns {Promise<void>}
     */
    async forceReset() {
        console.log('[PWA] Force reset initiated by user');
        showUpdateNotification('Resetting app...');

        await clearAllStorage();
        storeAppVersion(this.currentVersion);
        resetReloadCounter();

        this.scheduleReload(1000, 'Manual reset');
    }

    /**
     * Get diagnostic info about current state
     * Useful for debugging
     * @returns {object}
     */
    getDiagnostics() {
        const counter = getReloadCounter();

        return {
            app: {
                version: this.currentVersion,
                storedVersion: getStoredAppVersion(),
            },
            sw: {
                registered: 'serviceWorker' in navigator,
                ready: 'serviceWorker' in navigator ? 'checking...' : 'not available',
            },
            reload: {
                scheduled: this.reloadScheduled,
                inProgress: this.updateInProgress,
                counter: counter.count,
                counterLimit: counter.limit,
            },
            online: 'onLine' in navigator ? navigator.onLine : 'unknown',
            timestamp: new Date().toISOString(),
        };
    }
}

// Create singleton instance
const versionManager = new PWAVersionManager();

// Export for use in other modules
export default versionManager;

// Also expose to window for console debugging
if (process.env.NODE_ENV !== 'production' || true) {
    window.__PWA_VERSION_MANAGER__ = versionManager;
    window.__PWA_CHECK_VERSION__ = () => versionManager.checkVersion();
    window.__PWA_FORCE_RESET__ = () => versionManager.forceReset();
    window.__PWA_DIAGNOSTICS__ = () => versionManager.getDiagnostics();
}
