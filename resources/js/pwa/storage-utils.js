/**
 * PWA Storage Utilities
 * =====================
 * Helper functions for clearing local storage, IndexedDB, and managing
 * application data versioning and cache invalidation.
 */

/**
 * Clear all localStorage items
 * @returns {Promise<void>}
 */
export async function clearLocalStorage() {
    try {
        const keysToDelete = Object.keys(localStorage);
        keysToDelete.forEach((key) => {
            // Preserve session critical data if needed
            if (!key.startsWith('_preserve_')) {
                localStorage.removeItem(key);
            }
        });
        console.log('[PWA] localStorage cleared');
    } catch (error) {
        console.error('[PWA] Error clearing localStorage:', error);
    }
}

/**
 * Clear all IndexedDB databases
 * @returns {Promise<void>}
 */
export async function clearIndexedDB() {
    try {
        if (!window.indexedDB) {
            console.warn('[PWA] IndexedDB not available');
            return;
        }

        const databaseNames = await getIndexedDBNames();

        await Promise.all(
            databaseNames.map((name) =>
                new Promise((resolve, reject) => {
                    const request = window.indexedDB.deleteDatabase(name);

                    request.onsuccess = () => {
                        console.log(`[PWA] IndexedDB deleted: ${name}`);
                        resolve();
                    };

                    request.onerror = () => {
                        console.error(`[PWA] Error deleting IndexedDB: ${name}`, request.error);
                        reject(request.error);
                    };

                    request.onblocked = () => {
                        console.warn(`[PWA] Delete blocked for IndexedDB: ${name}`);
                    };
                })
            )
        );

        console.log('[PWA] All IndexedDB databases cleared');
    } catch (error) {
        console.error('[PWA] Error clearing IndexedDB:', error);
    }
}

/**
 * Get all IndexedDB database names
 * @returns {Promise<string[]>}
 */
export async function getIndexedDBNames() {
    try {
        if (!window.indexedDB || !window.indexedDB.databases) {
            // Fallback for browsers that don't support databases()
            return []; // Will need manual tracking as fallback
        }

        const databases = await window.indexedDB.databases();
        return databases.map((db) => db.name);
    } catch (error) {
        console.error('[PWA] Error getting IndexedDB names:', error);
        return [];
    }
}

/**
 * Clear application caches (service worker caches)
 * @returns {Promise<void>}
 */
export async function clearAppCaches() {
    try {
        if (!window.caches) {
            console.warn('[PWA] Caches API not available');
            return;
        }

        const cacheNames = await caches.keys();

        await Promise.all(
            cacheNames
                .filter((name) => name.startsWith('YPMMH-'))
                .map((name) => {
                    console.log(`[PWA] Deleting cache: ${name}`);
                    return caches.delete(name);
                })
        );

        console.log('[PWA] All application caches cleared');
    } catch (error) {
        console.error('[PWA] Error clearing caches:', error);
    }
}

/**
 * Clear all browser storage (localStorage, IndexedDB, Caches)
 * This is called when app version changes
 * @returns {Promise<void>}
 */
export async function clearAllStorage() {
    console.log('[PWA] Clearing all storage due to version change...');

    try {
        // Clear in parallel for speed
        await Promise.all([
            clearLocalStorage(),
            clearIndexedDB(),
            clearAppCaches(),
        ]);

        console.log('[PWA] ✓ All storage cleared successfully');
        return true;
    } catch (error) {
        console.error('[PWA] Error during full storage clear:', error);
        return false;
    }
}

/**
 * Store application version in localStorage
 * @param {string} version
 * @returns {void}
 */
export function storeAppVersion(version) {
    try {
        localStorage.setItem('APP_VERSION', version);
        localStorage.setItem('APP_VERSION_TIMESTAMP', new Date().toISOString());
        console.log(`[PWA] Version stored: ${version}`);
    } catch (error) {
        console.error('[PWA] Error storing version:', error);
    }
}

/**
 * Get stored application version from localStorage
 * @returns {string|null}
 */
export function getStoredAppVersion() {
    try {
        return localStorage.getItem('APP_VERSION');
    } catch (error) {
        console.error('[PWA] Error reading stored version:', error);
        return null;
    }
}

/**
 * Check if versions match
 * @param {string} currentVersion
 * @param {string} storedVersion
 * @returns {boolean}
 */
export function isVersionChange(currentVersion, storedVersion) {
    if (!storedVersion) {
        console.log('[PWA] First app load (no stored version)');
        return true;
    }

    const hasChanged = currentVersion !== storedVersion;

    if (hasChanged) {
        console.log(
            `[PWA] Version change detected: ${storedVersion} → ${currentVersion}`
        );
    }

    return hasChanged;
}

/**
 * Manage reload counter to prevent infinite reloads
 * @returns {object} { count: number, limit: number, exceeded: boolean }
 */
export function getReloadCounter() {
    const key = 'PWA_RELOAD_COUNT';
    const limitKey = 'PWA_RELOAD_LIMIT_TIME';
    const limit = 3; // Max 3 reloads
    const period = 30000; // In 30 seconds

    try {
        const now = Date.now();
        const lastTime = parseInt(localStorage.getItem(limitKey) || '0', 10);

        // Reset counter if period has passed
        if (now - lastTime > period) {
            localStorage.setItem(key, '0');
            localStorage.setItem(limitKey, now.toString());
            return { count: 0, limit, exceeded: false };
        }

        const count = parseInt(localStorage.getItem(key) || '0', 10) + 1;
        localStorage.setItem(key, count.toString());

        return {
            count,
            limit,
            exceeded: count > limit,
        };
    } catch (error) {
        console.error('[PWA] Error managing reload counter:', error);
        return { count: 0, limit, exceeded: false };
    }
}

/**
 * Reset reload counter after successful version update
 * @returns {void}
 */
export function resetReloadCounter() {
    try {
        localStorage.removeItem('PWA_RELOAD_COUNT');
        localStorage.removeItem('PWA_RELOAD_LIMIT_TIME');
        console.log('[PWA] Reload counter reset');
    } catch (error) {
        console.error('[PWA] Error resetting reload counter:', error);
    }
}

/**
 * Show maintenance notification to user
 * (use native browser APIs to be framework-agnostic)
 * @param {string} message
 * @returns {void}
 */
export function showUpdateNotification(message = 'Updating app to latest version...') {
    let banner = document.getElementById('pwa-update-banner-msg');

    if (!banner) {
        banner = document.createElement('div');
        banner.id = 'pwa-update-banner-msg';
        banner.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #0B4D73 0%, #075985 100%);
            color: white;
            padding: 16px 24px;
            text-align: center;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            z-index: 999999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        `;

        document.body.insertBefore(banner, document.body.firstChild);
    }

    banner.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="10" cy="10" r="9" stroke="white" stroke-width="1"/>
            <path d="M10 4v6l4 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        ${message}
    `;

    return banner;
}

/**
 * Hide update notification
 * @returns {void}
 */
export function hideUpdateNotification() {
    const banner = document.getElementById('pwa-update-banner-msg');
    if (banner) {
        banner.style.display = 'none';
        setTimeout(() => banner.remove(), 300);
    }
}

/**
 * Reload page with cache busting
 * Clears browser cache and reloads from server
 * @returns {void}
 */
export function reloadPageWithCacheBusting() {
    // Add cache buster query parameter
    const cacheBuster = `?refresh=${Date.now()}`;
    const currentPath = window.location.pathname + window.location.search;

    // Hard reload: Ctrl+Shift+R equivalent (no cache)
    window.location.href = currentPath + (currentPath.includes('?') ? '&' : '?') + `_nc=${Date.now()}`;

    // Fallback hard reload if location change doesn't trigger
    setTimeout(() => {
        location.reload(true); // force reload from server (true bypasses cache)
    }, 200);
}

export default {
    clearLocalStorage,
    clearIndexedDB,
    clearAppCaches,
    clearAllStorage,
    storeAppVersion,
    getStoredAppVersion,
    isVersionChange,
    getReloadCounter,
    resetReloadCounter,
    showUpdateNotification,
    hideUpdateNotification,
    reloadPageWithCacheBusting,
};
