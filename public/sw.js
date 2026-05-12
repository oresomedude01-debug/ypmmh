/**
 * YPMMH Progressive Web App - Service Worker with Dynamic Versioning
 * ==================================================================
 *
 * Caching Strategy:
 *   - Cache First: Static assets (CSS, JS, images, fonts)
 *   - Network First: Dynamic content (HTML pages, API responses)
 *   - Stale While Revalidate: CDN resources (Tailwind, FontAwesome, Google Fonts)
 *
 * Versioning: Automatically fetches version from app, invalidates old caches on deploy
 * Push Notifications: Handles Web Push Protocol notifications from server
 *                     AND page-triggered chat notifications via postMessage.
 */

// Version will be pulled from query parameter or fallback to date-based version
const urlParams = new URLSearchParams(location.search);
const APP_VERSION = urlParams.get('v') || new Date().toISOString().split('T')[0];
const CACHE_VERSION = APP_VERSION;
console.log(`[SW] Service Worker initialized (v${CACHE_VERSION})`);
const STATIC_CACHE  = `YPMMH-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `YPMMH-dynamic-${CACHE_VERSION}`;
const CDN_CACHE     = `YPMMH-cdn-${CACHE_VERSION}`;
const IMAGE_CACHE   = `YPMMH-images-${CACHE_VERSION}`;

// Maximum items in dynamic cache to prevent unbounded growth
const DYNAMIC_CACHE_LIMIT = 50;
const IMAGE_CACHE_LIMIT   = 100;

// Static assets to pre-cache during installation
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
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
            .then(() => self.skipWaiting())
            .catch((error) => {
                console.error('[SW] Pre-cache failed:', error);
            })
    );
});

// ===========================
// ACTIVATE EVENT
// ===========================
self.addEventListener('activate', (event) => {
    console.log(`[SW] Activating Service Worker ${CACHE_VERSION}`);

    event.waitUntil(
        caches.keys()
            .then((cacheNames) => Promise.all(
                cacheNames
                    .filter((name) => name.startsWith('YPMMH-') && !name.endsWith(CACHE_VERSION))
                    .map((name) => {
                        console.log(`[SW] Deleting old cache: ${name}`);
                        return caches.delete(name);
                    })
            ))
            .then(() => self.clients.claim())
            .then(() => self.clients.matchAll().then((clients) => {
                clients.forEach((client) => client.postMessage({
                    type: 'SW_UPDATED',
                    version: CACHE_VERSION,
                }));
            }))
    );
});

// ===========================
// FETCH EVENT
// ===========================
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    if (request.method !== 'GET') return;
    if (!url.protocol.startsWith('http')) return;
    if (url.searchParams.has('ajax')) return;
    if (isExcludedPath(url.pathname)) return;

    if (isCDNResource(url)) {
        event.respondWith(staleWhileRevalidate(request, CDN_CACHE));
    } else if (isStaticAsset(url)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
    } else if (isImageRequest(request, url)) {
        event.respondWith(cacheFirst(request, IMAGE_CACHE, IMAGE_CACHE_LIMIT));
    } else if (isNavigationRequest(request)) {
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
    } else {
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
    }
});

// ===========================
// CACHING STRATEGIES
// ===========================

async function cacheFirst(request, cacheName, limit = null) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) return cachedResponse;

        const networkResponse = await fetch(request);

        if (networkResponse && networkResponse.status === 200) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());
            if (limit) trimCache(cacheName, limit);
        }

        return networkResponse;
    } catch (error) {
        console.error('[SW] Cache First failed:', error);

        if (isImageRequest(request, new URL(request.url))) {
            return new Response(
                '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect fill="#f1f5f9" width="200" height="200"/><text fill="#94a3b8" font-family="sans-serif" font-size="14" x="50%" y="50%" text-anchor="middle" dy=".3em">Offline</text></svg>',
                { headers: { 'Content-Type': 'image/svg+xml' } }
            );
        }

        return new Response('Offline', { status: 503 });
    }
}

async function networkFirst(request, cacheName) {
    try {
        const networkResponse = await fetch(request);

        if (networkResponse && networkResponse.status === 200) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());
            trimCache(cacheName, DYNAMIC_CACHE_LIMIT);
        }

        return networkResponse;
    } catch (error) {
        console.log('[SW] Network failed, trying cache:', request.url);

        const cachedResponse = await caches.match(request);
        if (cachedResponse) return cachedResponse;

        if (isNavigationRequest(request)) {
            const offlinePage = await caches.match('/offline');
            if (offlinePage) return offlinePage;
        }

        return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
    }
}

async function staleWhileRevalidate(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);

    const fetchPromise = fetch(request).then((networkResponse) => {
        if (networkResponse && networkResponse.status === 200) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    }).catch(() => cachedResponse);

    return cachedResponse || fetchPromise;
}

// ===========================
// HELPER FUNCTIONS
// ===========================

function isStaticAsset(url) {
    return ['.css', '.js', '.woff', '.woff2', '.ttf', '.eot'].some(ext => url.pathname.endsWith(ext));
}

function isImageRequest(request, url) {
    return ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.ico'].some(ext => url.pathname.endsWith(ext)) ||
           (request.headers && request.headers.get('accept') && request.headers.get('accept').includes('image/'));
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
    return [
        '/api/', '/login', '/register', '/logout', '/password',
        '/sanctum/', '/broadcasting/', '/webhooks/',
        '/_debugbar/', '/telescope/', '/horizon/', '/livewire/',
    ].some(path => pathname.startsWith(path));
}

async function trimCache(cacheName, maxItems) {
    const cache = await caches.open(cacheName);
    const keys  = await cache.keys();

    if (keys.length > maxItems) {
        const deleteCount = keys.length - maxItems;
        for (let i = 0; i < deleteCount; i++) {
            await cache.delete(keys[i]);
        }
    }
}

// ===========================
// PUSH NOTIFICATIONS
// Server-sent Web Push Protocol
// ===========================

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
        notificationData = {
            title: 'YPMMH Notification',
            body:  event.data.text(),
            icon:  '/icons/icon-192x192.png',
            badge: '/icons/icon-72x72.png',
        };
    }

    const title   = notificationData.title || 'YPMMH – Young Productive Muslim Mentoring Hub';
    const options = {
        body:    notificationData.body   || 'You have a new notification',
        icon:    notificationData.icon   || '/icons/icon-192x192.png',
        badge:   notificationData.badge  || '/icons/icon-72x72.png',
        tag:     notificationData.tag    || 'ypmmh-notification',
        renotify: true,
        requireInteraction: notificationData.requireInteraction || false,
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival:    Date.now(),
            url:              notificationData.data?.url              || '/dashboard',
            notificationType: notificationData.data?.notificationType || 'general',
            ...notificationData.data,
        },
        actions: notificationData.actions || [
            { action: 'open',    title: 'Open'    },
            { action: 'dismiss', title: 'Dismiss' },
        ],
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
            .catch((err) => console.error('[SW] Error displaying notification:', err))
    );
});

// ===========================
// NOTIFICATION CLICK
// ===========================

self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked, action:', event.action);

    const notification = event.notification;
    const urlToOpen    = notification.data?.url || '/dashboard';

    notification.close();

    if (event.action === 'dismiss') return;

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((windowClients) => {
                // Focus existing window if the app is already open at that URL
                for (const client of windowClients) {
                    if (client.url.includes(urlToOpen) && 'focus' in client) {
                        return client.focus();
                    }
                }
                // No matching window — open a new one
                if (self.clients.openWindow) {
                    return self.clients.openWindow(urlToOpen);
                }
            })
    );
});

// ===========================
// NOTIFICATION CLOSE
// ===========================

self.addEventListener('notificationclose', (event) => {
    console.log('[SW] Notification dismissed:', event.notification.data?.notificationType);
});

// ===========================
// BACKGROUND SYNC (future)
// ===========================

self.addEventListener('sync', (event) => {
    if (event.tag === 'YPMMH-sync') {
        console.log('[SW] Background sync triggered');
    }
});

// ===========================
// MESSAGE HANDLER
// Handles messages from page context
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
            caches.open(DYNAMIC_CACHE).then((cache) => cache.addAll(urls))
        );
    }

    /**
     * SHOW_CHAT_NOTIFICATION
     * ──────────────────────
     * Fired by the notification-poll script in dashboard.blade.php when a new
     * chat message arrives and the user is NOT on that specific chat page.
     *
     * Using the SW (rather than plain `new Notification()`) lets the OS display
     * the notification even if the tab is hidden / minimised.
     *
     * Payload expected:
     *   { type, title, body, programId, url }
     */
    if (event.data && event.data.type === 'SHOW_CHAT_NOTIFICATION') {
        const { title, body, programId, url } = event.data;

        event.waitUntil(
            self.registration.showNotification(title || '💬 New Community Message', {
                body:     body || 'A new message was posted in your community.',
                icon:     '/icons/icon-192x192.png',
                badge:    '/icons/icon-72x72.png',
                tag:      'chat-program-' + (programId || 'unknown'),
                renotify: true,
                vibrate:  [200, 100, 200],
                data: {
                    url:              url || '/dashboard',
                    notificationType: 'chat',
                    programId:        programId,
                    dateOfArrival:    Date.now(),
                },
                actions: [
                    { action: 'open',    title: 'Open Chat' },
                    { action: 'dismiss', title: 'Dismiss'   },
                ],
            })
        );
    }
});
