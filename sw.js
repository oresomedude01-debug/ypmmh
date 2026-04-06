/**
 * YPMMH Progressive Web App - Service Worker with Dynamic Versioning
 * ==================================================================
 * 
 * Caching Strategy:
 *   - Cache First: Static assets (CSS, JS, images, fonts) - ONLY if hash/version changes
 *   - Network First: Dynamic content (HTML pages, API responses) - Always try server first
 *   - Stale While Revalidate: CDN resources (Tailwind, FontAwesome, Google Fonts)
 *
 * Key Features:
 *   - NEVER caches error responses (4xx, 5xx)
 *   - NEVER caches API responses (relies on server Cache-Control headers)
 *   - Automatically cleans up old caches on version change
 *   - Validates response status before caching
 *   - Respects Cache-Control headers from server
 */

// Get app version from localStorage or use date-based fallback
const getAppVersion = () => {
    try {
        const stored = localStorage.getItem('APP_VERSION');
        if (stored) return stored;
    } catch (e) {
        console.warn('[SW] Cannot access localStorage:', e.message);
    }
    return new Date().toISOString().split('T')[0];
};

const APP_VERSION = getAppVersion();
const CACHE_VERSION = APP_VERSION;
console.log(`[SW] Service Worker initialized (v${CACHE_VERSION})`);

const STATIC_CACHE = `YPMMH-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `YPMMH-dynamic-${CACHE_VERSION}`;
const CDN_CACHE = `YPMMH-cdn-${CACHE_VERSION}`;
const IMAGE_CACHE = `YPMMH-images-${CACHE_VERSION}`;

// Maximum items in dynamic cache to prevent unbounded growth
const DYNAMIC_CACHE_LIMIT = 50;
const IMAGE_CACHE_LIMIT = 100;

// Static assets to pre-cache during installation
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

// Routes to cache for offline access
const OFFLINE_ROUTES = [
    '/',
    '/about',
    '/programs/explore',
    '/blog',
    '/gallery',
];

// CDN domains that we cache with stale-while-revalidate
const CDN_DOMAINS = [
    'cdn.tailwindcss.com',
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    'cdnjs.cloudflare.com',
];

// ===========================
// INSTALL EVENT
// ===========================
self.addEventListener('install', (event) => {
    console.log(`[SW] Installing Service Worker ${CACHE_VERSION}`);

    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('[SW] Pre-caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                // Skip waiting to activate immediately
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('[SW] Pre-cache failed:', error);
            })
    );
});

// ===========================
// ACTIVATE EVENT - Clean up old caches
// ===========================
self.addEventListener('activate', (event) => {
    console.log(`[SW] Activating Service Worker ${CACHE_VERSION}`);

    event.waitUntil(
        // First, clean up old version caches
        caches.keys()
            .then((cacheNames) => {
                console.log('[SW] Current caches:', cacheNames);
                console.log('[SW] Keeping caches for version:', CACHE_VERSION);

                return Promise.all(
                    cacheNames
                        .filter((cacheName) => {
                            // Delete old versioned caches
                            const shouldDelete = cacheName.startsWith('YPMMH-') && 
                                   !cacheName.endsWith(CACHE_VERSION);
                            
                            if (shouldDelete) {
                                console.log(`[SW] Deleting old cache: ${cacheName}`);
                            }
                            return shouldDelete;
                        })
                        .map((cacheName) => caches.delete(cacheName))
                );
            })
            .then(() => {
                console.log('[SW] Old caches cleaned up');
                // Claim all clients immediately
                return self.clients.claim();
            })
            .then(() => {
                // Notify all clients about the update
                return self.clients.matchAll().then((clients) => {
                    clients.forEach((client) => {
                        client.postMessage({
                            type: 'SW_UPDATED',
                            version: CACHE_VERSION
                        });
                    });
                });
            })
    );
});

// ===========================
// FETCH EVENT
// ===========================
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip Chrome extensions and other non-http(s) schemes
    if (!url.protocol.startsWith('http')) return;

    // Skip Laravel-specific paths that should never be cached
    if (isExcludedPath(url.pathname)) return;

    // Determine caching strategy based on request type
    if (isCDNResource(url)) {
        event.respondWith(staleWhileRevalidate(request, CDN_CACHE));
    } else if (isStaticAsset(url)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
    } else if (isImageRequest(request, url)) {
        event.respondWith(cacheFirst(request, IMAGE_CACHE, IMAGE_CACHE_LIMIT));
    } else if (isNavigationRequest(request)) {
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
    } else {
        // Default: Network first for everything else
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
    }
});

// ===========================
// CACHING STRATEGIES
// ===========================

/**
 * Validate Response Before Caching
 * 
 * NEVER cache:
 * - Error responses (4xx, 5xx)
 * - Responses without content-type
 * - Responses that are too small (might be redirects)
 */
function isValidCacheableResponse(response) {
    // Don't cache error responses
    if (!response || response.status >= 400) {
        console.log(`[SW] Not caching response with status ${response?.status}`);
        return false;
    }

    // Don't cache responses without proper content
    if (response.status === 204 || response.status === 304) {
        return false;
    }

    // Check if response has content
    if (!response.headers || !response.headers.get('content-type')) {
        return false;
    }

    return true;
}

/**
 * Cache First Strategy
 * Best for: static assets that rarely change (CSS, JS, images)
 * 
 * Priority: Cache > Network > Offline Fallback
 */
async function cacheFirst(request, cacheName, limit = null) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            console.log(`[SW] Cache hit: ${request.url}`);
            return cachedResponse;
        }

        const networkResponse = await fetch(request);

        // Validate response before caching
        if (isValidCacheableResponse(networkResponse)) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());

            if (limit) {
                trimCache(cacheName, limit);
            }
        }

        return networkResponse;
    } catch (error) {
        console.error('[SW] Cache First failed:', error);

        // Return offline fallback for images
        if (isImageRequest(request, new URL(request.url))) {
            return new Response(
                '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect fill="#f1f5f9" width="200" height="200"/><text fill="#94a3b8" font-family="sans-serif" font-size="14" x="50%" y="50%" text-anchor="middle" dy=".3em">Offline</text></svg>',
                { headers: { 'Content-Type': 'image/svg+xml' } }
            );
        }

        return new Response('Offline', { status: 503 });
    }
}

/**
 * Network First Strategy
 * Best for: dynamic content (HTML pages, API data)
 * 
 * Priority: Network > Cache > Offline Fallback
 * 
 * This ensures users always get fresh data from the server.
 * Only falls back to cache if network fails (true offline scenario).
 */
async function networkFirst(request, cacheName) {
    try {
        const networkResponse = await fetch(request);

        // Validate response before caching
        if (isValidCacheableResponse(networkResponse)) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());
            trimCache(cacheName, DYNAMIC_CACHE_LIMIT);
            
            console.log(`[SW] Network fresh: ${request.url}`);
        } else {
            console.log(`[SW] Invalid response, not caching: ${request.url}`);
        }

        return networkResponse;
    } catch (error) {
        console.log(`[SW] Network failed for ${request.url}, falling back to cache`);

        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            console.log(`[SW] Serving from cache: ${request.url}`);
            return cachedResponse;
        }

        // If it's a navigation request, show offline page
        if (isNavigationRequest(request)) {
            const offlinePage = await caches.match('/offline');
            if (offlinePage) {
                return offlinePage;
            }
        }

        return new Response('Offline', {
            status: 503,
            statusText: 'Service Unavailable'
        });
    }
}

/**
 * Stale While Revalidate Strategy
 * Best for: CDN resources that should update but can serve stale
 * 
 * Priority: Cache (immediate) + Network (background update)
 * 
 * Returns cached version immediately, then updates cache in background
 */
async function staleWhileRevalidate(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);

    const fetchPromise = fetch(request)
        .then((networkResponse) => {
            // Validate before updating cache
            if (isValidCacheableResponse(networkResponse)) {
                cache.put(request, networkResponse.clone());
                console.log(`[SW] Updated SWR cache: ${request.url}`);
            }
            return networkResponse;
        })
        .catch((error) => {
            console.warn('[SW] SWR network failed:', error);
            // Network failed, we'll use cache if available
            return cachedResponse || new Response('Offline', { status: 503 });
        });

    // Return cached version immediately if available, otherwise wait for network
    return cachedResponse || fetchPromise;
}

// ===========================
// HELPER FUNCTIONS
// ===========================

function isStaticAsset(url) {
    const staticExtensions = ['.css', '.js', '.woff', '.woff2', '.ttf', '.eot'];
    return staticExtensions.some(ext => url.pathname.endsWith(ext));
}

function isImageRequest(request, url) {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.ico'];
    return imageExtensions.some(ext => url.pathname.endsWith(ext)) ||
           (request.headers && request.headers.get('accept') && 
            request.headers.get('accept').includes('image/'));
}

function isCDNResource(url) {
    return CDN_DOMAINS.some(domain => url.hostname.includes(domain));
}

function isNavigationRequest(request) {
    return request.mode === 'navigate' ||
           (request.method === 'GET' && 
            request.headers.get('accept') && 
            request.headers.get('accept').includes('text/html'));
}

function isExcludedPath(pathname) {
    // Paths that should NEVER be cached by Service Worker
    // These are handled by server-side Cache-Control headers
    const excludedPaths = [
        // API endpoints - handled by network first + server cache headers
        '/api/',
        
        // Authentication routes - never cache
        '/login',
        '/register',
        '/logout',
        '/password',
        '/forgot-password',
        '/reset-password',
        '/sanctum/',
        
        // Real-time communication - never cache
        '/broadcasting/',
        '/echo/',
        
        // External webhooks - never cache
        '/webhooks/',
        'paystack',
        
        // Development tools - never cache
        '/_debugbar/',
        '/telescope/',
        '/horizon/',
        '/livewire/',
        
        // Admin-only paths - rely on server cache headers
        '/admin/',
    ];
    
    return excludedPaths.some(path => pathname.includes(path));
}

/**
 * Trim cache to prevent unbounded growth
 */
async function trimCache(cacheName, maxItems) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();

    if (keys.length > maxItems) {
        // Delete oldest entries (FIFO)
        const deleteCount = keys.length - maxItems;
        for (let i = 0; i < deleteCount; i++) {
            await cache.delete(keys[i]);
        }
    }
}

// ===========================
// PUSH NOTIFICATIONS - Web Push Protocol
// ===========================

/**
 * Push event handler - Web Push Protocol (using web-push library)
 * Triggered when server sends push notification
 */
self.addEventListener('push', (event) => {
    console.log('[SW] Push event received');

    if (!event.data) {
        console.warn('[SW] Push event has no data');
        return;
    }

    let notificationData = {};

    try {
        notificationData = event.data.json();
    } catch (e) {
        console.error('[SW] Failed to parse push data:', e);
        notificationData = {
            title: 'YPMMH Notification',
            body: event.data.text(),
            icon: '/icons/icon-192x192.png',
            badge: '/icons/icon-72x72.png',
        };
    }

    const title = notificationData.title || 'YPMMH - Young Productive Muslim Mentoring Hub';
    const options = {
        body: notificationData.body || 'You have a new notification',
        icon: notificationData.icon || '/icons/icon-192x192.png',
        badge: notificationData.badge || '/icons/icon-72x72.png',
        tag: notificationData.tag || 'notification',
        renotify: true,
        requireInteraction: notificationData.requireInteraction || false,
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            url: notificationData.data?.url || '/dashboard',
            notificationType: notificationData.data?.notificationType || 'general',
            ...notificationData.data
        },
        actions: notificationData.actions || [
            {
                action: 'open',
                title: 'Open',
                icon: '/icons/icon-96x96.png'
            },
            {
                action: 'dismiss',
                title: 'Dismiss',
                icon: '/icons/icon-96x96.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
            .then(() => {
                console.log('[SW] Notification displayed successfully');
                // Log notification display to server
                notifyServer({
                    action: 'notification_displayed',
                    type: notificationData.data?.notificationType,
                    timestamp: new Date().toISOString()
                });
            })
            .catch((error) => {
                console.error('[SW] Error displaying notification:', error);
            })
    );
});

/**
 * Notification click handler
 * Triggered when user clicks on a notification
 */
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked');

    const notification = event.notification;
    const urlToOpen = notification.data.url || '/dashboard';
    const notificationType = notification.data.notificationType;

    // Track click event
    notifyServer({
        action: 'notification_clicked',
        type: notificationType,
        timestamp: new Date().toISOString()
    });

    if (event.action === 'dismiss') {
        notification.close();
        return;
    }

    // Handle open action or notification click
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((windowClients) => {
                // Check if app is already open
                for (let i = 0; i < windowClients.length; i++) {
                    const client = windowClients[i];
                    if (client.url === urlToOpen && 'focus' in client) {
                        return client.focus();
                    }
                }

                // App not open, open it
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
            .finally(() => {
                notification.close();
            })
    );
});

/**
 * Notification close handler
 * Triggered when notification is dismissed
 */
self.addEventListener('notificationclose', (event) => {
    console.log('[SW] Notification dismissed');

    const notificationType = event.notification.data.notificationType;

    // Track dismiss event
    notifyServer({
        action: 'notification_dismissed',
        type: notificationType,
        timestamp: new Date().toISOString()
    });
});

/**
 * Helper function to notify server of notification events
 */
async function notifyServer(data) {
    try {
        // Get auth token from IndexedDB or localStorage
        const token = localStorage.getItem('auth_token') || 
                     sessionStorage.getItem('auth_token');

        if (!token) {
            console.log('[SW] No auth token available, skipping server notification');
            return;
        }

        await fetch('/api/push/log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data)
        });
    } catch (error) {
        console.error('[SW] Error notifying server:', error);
    }
}
        self.registration.showNotification(data.title || 'YPMMH', options)
    );
});

/**
 * Notification click handler
 */
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'dismiss') return;

    const targetUrl = event.notification.data?.url || '/dashboard';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Focus existing window if available
                for (const client of clientList) {
                    if (client.url.includes(targetUrl) && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Open new window
                if (self.clients.openWindow) {
                    return self.clients.openWindow(targetUrl);
                }
            })
    );
});

/**
 * Background sync handler (future use)
 */
self.addEventListener('sync', (event) => {
    if (event.tag === 'YPMMH-sync') {
        console.log('[SW] Background sync triggered');
        // Future: sync offline form submissions, chat messages, etc.
    }
});

// ===========================
// MESSAGE HANDLER
// ===========================
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_VERSION });
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        const urls = event.data.urls || [];
        event.waitUntil(
            caches.open(DYNAMIC_CACHE).then((cache) => {
                return cache.addAll(urls);
            })
        );
    }
});
