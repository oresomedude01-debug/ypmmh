/**
 * PWA Cache Management & Invalidation Strategy
 * ===============================================
 * 
 * Handles:
 * 1. Service Worker registration and update detection
 * 2. Cache invalidation on data mutations (post/put/delete)
 * 3. localStorage versioning to force cache refresh
 * 4. User notification of updates
 */

class CacheManager {
    constructor() {
        this.sw = null;
        this.appVersion = null;
        this.init();
    }

    /**
     * Initialize cache management
     */
    async init() {
        if (!('serviceWorker' in navigator)) {
            console.log('[Cache] Service Worker not supported');
            return;
        }

        try {
            // Register Service Worker
            this.sw = await navigator.serviceWorker.register('/sw.js', {
                scope: '/'
            });
            
            console.log('[Cache] Service Worker registered');

            // Check for updates on page load
            this.sw.update();

            // Listen for Service Worker updates
            this.sw.addEventListener('updatefound', () => this.handleSWUpdate());

            // Listen for messages from Service Worker
            navigator.serviceWorker.addEventListener('message', (event) => {
                this.handleSWMessage(event.data);
            });

            // Get current app version
            this.appVersion = this.getStoredVersion();
            
            // Initialize cache invalidation on network requests
            this.setupInterceptors();

        } catch (error) {
            console.error('[Cache] Service Worker registration failed:', error);
        }
    }

    /**
     * Handle Service Worker updates
     */
    handleSWUpdate() {
        console.log('[Cache] Service Worker update detected');

        const newWorker = this.sw.installing;

        newWorker?.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                // New SW is ready
                this.notifyUpdate();
            }
        });
    }

    /**
     * Handle messages from Service Worker
     */
    handleSWMessage(data) {
        switch (data.type) {
            case 'SW_UPDATED':
                console.log('[Cache] SW version updated:', data.version);
                // Invalidate old caches
                this.invalidateOldCaches(data.version);
                break;

            case 'CACHE_CLEARED':
                console.log('[Cache] Cache cleared by SW');
                break;

            default:
                console.log('[Cache] Unknown message from SW:', data);
        }
    }

    /**
     * Invalidate caches when data mutates (POST, PUT, DELETE)
     * 
     * This ensures stale data doesn't persist after mutations
     */
    setupInterceptors() {
        const originalFetch = window.fetch;

        window.fetch = async (...args) => {
            const [resource, config = {}] = args;
            const method = (config.method || 'GET').toUpperCase();

            // Call original fetch
            const response = await originalFetch(...args);

            // For state-changing requests, invalidate caches
            if (['POST', 'PUT', 'DELETE', 'PATCH'].includes(method)) {
                // Only invalidate if successful (2xx status)
                if (response.status >= 200 && response.status < 300) {
                    console.log(`[Cache] State mutation detected (${method}), clearing dynamic cache`);
                    
                    // Clear specific cache related to this request
                    this.invalidateDynamicCache(resource, method);
                    
                    // Notify Service Worker to refresh
                    this.notifySWOfMutation(resource, method);
                }
            }

            return response;
        };
    }

    /**
     * Invalidate dynamic cache for updated resources
     */
    async invalidateDynamicCache(resource, method) {
        const cacheNames = await caches.keys();
        const dynamicCaches = cacheNames.filter(name => 
            name.includes('dynamic') || name.includes('YPMMH-dynamic')
        );

        console.log('[Cache] Clearing dynamic caches:', dynamicCaches);

        for (const cacheName of dynamicCaches) {
            const cache = await caches.open(cacheName);
            const keys = await cache.keys();

            // Remove cached responses for related URLs
            for (const request of keys) {
                const url = new URL(request.url);
                
                // Match related resource URLs
                if (this.isRelatedUrl(resource, url)) {
                    console.log('[Cache] Removing stale cache:', request.url);
                    await cache.delete(request);
                }
            }
        }
    }

    /**
     * Determine if a URL is related to the mutated resource
     */
    isRelatedUrl(resource, cachedUrl) {
        const resourceUrl = new URL(resource, window.location.origin);
        const resourcePath = resourceUrl.pathname;
        const cachedPath = cachedUrl.pathname;

        // Same path
        if (resourcePath === cachedPath) return true;

        // Resource is an item, cached is listing (e.g. /api/items/1 vs /api/items)
        if (resourcePath.includes('/api/')) {
            const basePath = resourcePath.split('/').slice(0, -1).join('/');
            if (cachedPath === basePath + '/' || cachedPath === basePath) {
                return true;
            }
        }

        // Same entity type (e.g. /programs)
        const resourceBase = resourcePath.split('/')[1];
        const cachedBase = cachedPath.split('/')[1];
        if (resourceBase && cachedBase && resourceBase === cachedBase) {
            return true;
        }

        return false;
    }

    /**
     * Notify Service Worker of a mutation event
     */
    async notifySWOfMutation(resource, method) {
        if (!navigator.serviceWorker.controller) {
            console.log('[Cache] No active Service Worker to notify');
            return;
        }

        navigator.serviceWorker.controller.postMessage({
            type: 'MUTATION_DETECTED',
            resource: resource,
            method: method,
            timestamp: new Date().toISOString()
        });
    }

    /**
     * Invalidate old caches when version changes
     */
    async invalidateOldCaches(newVersion) {
        const oldVersion = this.appVersion;
        
        if (oldVersion && oldVersion !== newVersion) {
            console.log(`[Cache] Version changed from ${oldVersion} to ${newVersion}`);
            console.log('[Cache] Old caches should be auto-deleted by SW');
            
            // Update stored version
            this.storeVersion(newVersion);
            this.appVersion = newVersion;
        }
    }

    /**
     * Store app version in localStorage
     */
    storeVersion(version) {
        try {
            localStorage.setItem('APP_VERSION', version);
        } catch (e) {
            console.warn('[Cache] Cannot write to localStorage:', e.message);
        }
    }

    /**
     * Get stored app version
     */
    getStoredVersion() {
        try {
            return localStorage.getItem('APP_VERSION') || '1.0.0';
        } catch (e) {
            console.warn('[Cache] Cannot read from localStorage:', e.message);
            return '1.0.0';
        }
    }

    /**
     * Notify user of Service Worker update
     */
    notifyUpdate() {
        // You can implement a toast/notification here
        console.log('[Cache] New version available. Refresh to update.');
        
        // Optional: Auto-refresh
        // This will show a notification to the user
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('App Updated', {
                body: 'A new version is available. Refreshing...',
                icon: '/icons/icon-192x192.png'
            });

            // Auto-refresh after a delay
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }
    }

    /**
     * Force clear all caches (emergency use only)
     */
    async clearAllCaches() {
        const cacheNames = await caches.keys();
        console.log('[Cache] Clearing all caches:', cacheNames);
        
        return Promise.all(
            cacheNames.map(cacheName => caches.delete(cacheName))
        );
    }

    /**
     * Get cache statistics for debugging
     */
    async getCacheStats() {
        const cacheNames = await caches.keys();
        const stats = {};

        for (const cacheName of cacheNames) {
            const cache = await caches.open(cacheName);
            const keys = await cache.keys();
            stats[cacheName] = {
                size: keys.length,
                urls: keys.map(k => new URL(k.url).pathname)
            };
        }

        return stats;
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cacheManager = new CacheManager();
    });
} else {
    window.cacheManager = new CacheManager();
}
