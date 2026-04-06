# PWA Caching & Stale Data Fix - Implementation Guide

## 🎯 Overview

This implementation fixes the stale data issue where deleted or updated data continues appearing on previously used devices. The solution involves a multi-layer approach:

1. **Service Worker Strategy**: Network-First for dynamic content, Cache-First for static assets
2. **HTTP Cache Headers**: Strict no-cache on dynamic routes
3. **Cache Invalidation**: Automatic cleanup on data mutations
4. **Response Validation**: Never cache error responses
5. **Version-based Cache Busting**: Automatic cache cleanup on app updates

---

## 📋 Key Changes

### 1. Enhanced Service Worker (`sw.js`)

**New Features:**
- ✅ **Response Validation**: Never caches 4xx/5xx error responses
- ✅ **Network First Strategy**: Always tries server first for dynamic content
- ✅ **Better Logging**: Enhanced console logs for debugging
- ✅ **Cache Cleanup**: Automatically deletes old cached versions
- ✅ **Intelligent Path Exclusion**: Prevents caching of API, auth, and admin routes

**Key Strategies:**

```javascript
// Dynamic content (HTML pages, user data)
// Priority: Network → Cache → Offline Fallback
networkFirst(request, DYNAMIC_CACHE)

// Static assets (CSS, JS)
// Priority: Cache → Network → Offline Fallback
cacheFirst(request, STATIC_CACHE)

// CDN resources (Fonts, Tailwind)
// Priority: Cache (immediate) + Network (background update)
staleWhileRevalidate(request, CDN_CACHE)
```

### 2. Cache Control Middleware (`app/Http/Middleware/CacheControlMiddleware.php`)

**Granular Cache Policy:**

| Request Type | Strategy | Headers |
|---|---|---|
| API Requests | No Cache | `no-cache, no-store, must-revalidate` |
| Error Responses (4xx/5xx) | No Cache | `no-cache, no-store, must-revalidate` |
| Authenticated Routes | No Store | `private, no-cache, no-store` |
| Public Routes | Revalidate | `public, max-age=0, must-revalidate` |
| Redirects | No Cache | `no-cache, no-store, must-revalidate` |

**Benefit:** Ensures proper caching at multiple levels (browser, Service Worker, edge caches)

### 3. Cache Invalidation Manager (`resources/js/cache-management.js`)

**Automatic Cache Invalidation On:**
- POST requests (create new data)
- PUT requests (update data)
- DELETE requests (remove data)
- PATCH requests (modify data)

**How It Works:**
1. Intercepts all fetch requests
2. Detects state-changing operations
3. Clears related cache entries
4. Notifies Service Worker of changes

**Example: When user deletes a program:**

```
1. DELETE /api/programs/123 → Success
2. CacheManager detects mutation
3. Clears cache for:
   - /api/programs/123 (specific item)
   - /api/programs/ (listing page)
   - /programs/catalog (UI page)
4. Next page load gets fresh data
```

### 4. Response Validation

**Never Caches:**
- HTTP error responses (status >= 400)
- Responses without content-type
- Empty responses (204, 304)
- Authentication-related responses

**Before:**
```javascript
// Bad: Caches regardless of status
if (networkResponse && networkResponse.status === 200) {
    cache.put(request, networkResponse.clone());
}
```

**After:**
```javascript
// Good: Validates response before caching
if (isValidCacheableResponse(networkResponse)) {
    cache.put(request, networkResponse.clone());
}

function isValidCacheableResponse(response) {
    // Don't cache errors
    if (!response || response.status >= 400) return false;
    // Don't cache empty responses
    if (response.status === 204 || response.status === 304) return false;
    // Must have content
    if (!response.headers?.get('content-type')) return false;
    return true;
}
```

---

## 🔄 Data Consistency Flow

### Scenario 1: User Updates Program Title

```
User A edits: "Basic Islamic Studies" → "Advanced Quran"

1. Frontend sends: PUT /api/programs/123
2. Server updates database
3. CacheManager intercepts response
4. Detects PUT mutation on /api/programs/123
5. Clears cache:
   - /api/programs/123
   - /api/programs
   - /programs/catalog
6. Users B and C:
   - Reload page
   - Service Worker uses Network First
   - Fetches fresh data from server
   - Gets "Advanced Quran"
```

### Scenario 2: User Deletes an Enrollment

```
User A deletes enrollment in "Age 7-9 Islamic Classes"

1. Frontend sends: DELETE /api/enrollments/456
2. Server deletes from database
3. CacheManager intercepts success
4. Clears cache:
   - /api/enrollments/456
   - /api/enrollments
   - /dashboard (enrollment count updated)
5. Users B and C:
   - Next dashboard load = Network First
   - Gets fresh enrollment count
   - Sees enrollment removed
```

### Scenario 3: App Version Update (Deployment)

```
Admin deploys v2.0.0

1. New SW version deployed to CDN
2. Browser detects SW update
3. Activate event triggers
4. Old cache versions deleted:
   - YPMMH-static-v1.9.0 ✓ Deleted
   - YPMMH-dynamic-v1.9.0 ✓ Deleted
   - YPMMH-images-v1.9.0 ✓ Deleted
5. New caches created:
   - YPMMH-static-v2.0.0 ✓ Fresh
   - YPMMH-dynamic-v2.0.0 ✓ Fresh
   - YPMMH-images-v2.0.0 ✓ Fresh
```

---

## 🛡️ Protected Routes (Never Cached)

The Service Worker automatically excludes these from caching:

```
/api/*              - API endpoints
/login              - Authentication
/register           - Authentication
/logout             - Authentication
/sanctum/*          - Laravel Sanctum
/broadcasting/*     - Real-time features
/webhooks/*         - External integrations
/_debugbar/*        - Development tools
/telescope/*        - Development tools
/admin/*            - Admin panel
```

**Why?** These routes have their own Cache-Control headers and should always respect server decisions.

---

## 📊 Cache Hierarchy (Request Resolution)

### Network First (Dynamic Content)

```
User requests: /programs/123

1. Service Worker intercepts
2. Try: Fetch from server
   ✓ Success (fresh data)
   ↓
   Cache response
   Return to user
   ✗ Failed (offline)
   ↓
3. Try: Get from cache
   ✓ Found (return stale data)
   ✗ Not found
   ↓
4. Show: Offline fallback page
```

### Cache First (Static Assets)

```
User requests: /build/app.js (hash-versioned)

1. Service Worker intercepts
2. Try: Get from cache
   ✓ Found (immediate return, no wait)
   ✗ Not found
   ↓
3. Try: Fetch from server
   ✓ Success (cache for future)
   ✗ Failed (offline)
   ↓
4. Show: Offline fallback
```

---

## 🔧 Configuration

### Enable in Production

Ensure in your production `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_VERSION=2.0.0
```

Clear caches after deployment:
```bash
php artisan config:cache
php artisan view:cache
php artisan cache:clear
```

### Monitor Cache Health

Check what's cached (browser console):

```javascript
// View all caches
cacheManager.getCacheStats().then(stats => console.log(stats));

// Clear all caches (emergency only)
cacheManager.clearAllCaches();

// View Service Worker logs
// DevTools → Application → Service Workers → View Console
```

---

## 📈 Expected Improvements

| Metric | Before | After |
|--------|--------|-------|
| Stale data after delete | ✗ Persists | ✓ Cleared |
| Stale data after update | ✗ Shows old | ✓ Shows new |
| Offline capability | ✓ Works | ✓ Works |
| Push notifications | ✓ Works | ✓ Works |
| App installability | ✓ Works | ✓ Works |
| Cache bloat on old devices | ✗ Unbounded | ✓ Limited |
| Deployment updates | ✗ Manual refresh needed | ✓ Auto-updates |

---

## 🐛 Debugging

### Check Service Worker Status

```javascript
// In browser console:
navigator.serviceWorker.getRegistrations().then(regs => {
    regs.forEach(reg => {
        console.log('Active:', reg.active?.scriptURL);
        console.log('Installing:', reg.installing?.scriptURL);
        console.log('Waiting:', reg.waiting?.scriptURL);
    });
});
```

### View Cache Contents

```javascript
// List all cached URLs
caches.keys().then(names => {
    names.forEach(name => {
        caches.open(name).then(cache => {
            cache.keys().then(requests => {
                console.log(`${name}:`, requests.map(r => r.url));
            });
        });
    });
});
```

### Force Service Worker Update

```bash
# Development
Ctrl+Shift+Delete  # Clear browser cache (Chrome)

# Production - Unregister and refresh
navigator.serviceWorker.getRegistrations().then(regs => {
    regs.forEach(reg => reg.unregister());
});
location.reload();
```

---

## 🚀 Next Steps

1. **Test locally**: Clear caches, create/update/delete data, verify freshness
2. **Deploy to cPanel**: Use deployment guide from DEPLOYMENT_GUIDE.md
3. **Monitor logs**: Check `storage/logs/laravel.log` for errors
4. **User feedback**: Ask early adopters if stale data persists
5. **Iterate**: Adjust excluded paths if issues found

---

## 📚 Related Files

- [`sw.js`](sw.js) - Service Worker main file
- [`resources/js/cache-management.js`](resources/js/cache-management.js) - Cache invalidation
- [`app/Http/Middleware/CacheControlMiddleware.php`](app/Http/Middleware/CacheControlMiddleware.php) - Cache headers
- [`resources/views/app.blade.php`](resources/views/app.blade.php) - Layout include
- [`resources/views/partials/pwa.blade.php`](resources/views/partials/pwa.blade.php) - PWA config

---

## ❓ FAQ

**Q: Will this affect offline mode?**
A: No, Network First strategy only uses cache when network fails.

**Q: Does this break push notifications?**
A: No, push notifications are independent of caching logic.

**Q: What about Google Fonts caching?**
A: CDN resources use Stale-While-Revalidate, so they're cached but updated in background.

**Q: Can users opt-out of caching?**
A: Not directly, but you can disable Service Worker in browser settings.

**Q: When are caches cleared?**
A: Automatically when app version changes, or manually with `cacheManager.clearAllCaches()`.
