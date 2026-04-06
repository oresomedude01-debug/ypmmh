# 🚀 PWA Version System - Deployment Guide

## Implementation Complete ✅

The PWA version and cache invalidation system has been fully implemented and is production-ready.

---

## �️ Production Error Handling & Security

### **Enable Custom Error Pages**

To hide sensitive error information in production and show friendly error pages:

1. **Update your production .env file:**

```bash
# Production Settings
APP_ENV=production
APP_DEBUG=false
APP_DEBUG_HIDE_SENSITIVE_DETAILS=true
APP_URL=https://yourdomain.com
```

2. **Verify custom error pages exist in:**
   - `resources/views/errors/401.blade.php` - Unauthorized
   - `resources/views/errors/403.blade.php` - Access Denied
   - `resources/views/errors/404.blade.php` - Not Found
   - `resources/views/errors/500.blade.php` - Server Error
   - `resources/views/errors/503.blade.php` - Service Unavailable

3. **Clear application caches after deployment:**

```bash
php artisan config:cache
php artisan view:cache
php artisan cache:clear
```

### **What Gets Hidden in Production**
- Exception details and stack traces
- File paths and line numbers
- Database query information
- Environment variables
- Sensitive server configuration

### **Errors are Still Logged**
All errors are logged to: `storage/logs/laravel.log`

You can monitor errors via:
```bash
tail -f storage/logs/laravel.log
```

---

## �📋 Pre-Deployment Checklist

### 1. **Update Your .env File**

```bash
# Add or update this line in your production .env:
APP_VERSION=1.0.0
```

### 2. **Push Latest Code to GitHub**

```bash
git add -A
git commit -m "feat: implement PWA version and cache invalidation system"
git push origin main
```

### 3. **Deploy to cPanel**

```bash
# SSH into your server
ssh user@ypmmh.com.ng

# Navigate to app root
cd ~/public_html/html  # or your app directory

# Pull latest code
git pull origin main

# Clear caches
php artisan config:cache
php artisan view:cache
php artisan cache:clear
```

---

## 🎯 What Happens After Deployment

### **For New Users (First Visit)**
1. App loads normally
2. Version is stored in localStorage
3. ✅ No impact - fresh install

### **For Existing Users (Returning Visit)**
1. Page loads with NEW `APP_VERSION` from server
2. System detects version mismatch
3. Shows "Updating app..." banner
4. Clears: localStorage, IndexedDB, Service Worker caches
5. Stores new version number
6. Reloads page (fresh from server)
7. Service Worker installs/activates
8. ✅ User has latest version instantly

### **Time Impact**
- Version check: `<100ms`
- On version change: `1-3 seconds` (includes page reload)
- User sees: "Updating app..." banner

---

## 🔑 Key Features Implemented

✅ **Automatic Detection**
- No manual user action needed
- Detects version on every pageload

✅ **Complete Data Clearing**
- localStorage cleared
- IndexedDB databases cleared
- Service Worker caches cleared

✅ **Safe Reloading**
- Prevents infinite reload loops
- Max 3 reloads in 30 seconds
- Shows error if something goes wrong

✅ **Dynamic Versioning**
- Pull version from `config/app.php`
- Service Worker caches named with version
- Old caches auto-deleted on deploy

✅ **Push Notifications Still Work**
- Service Worker enhanced, not changed
- Push notifications unaffected
- All offline capabilities preserved

---

## 📊 Files Modified/Created

### New Files
```
resources/js/pwa/
  ├── storage-utils.js          (Storage management helpers)
  ├── version-manager.js        (Main version system)
  ├── version-check.min.js      (Vanilla JS version for all browsers)
  └── init.js                   (ES6 initializer)

config/
  └── app.php                   (Added APP_VERSION config)

app/Http/Middleware/
  └── NoCacheMiddleware.php     (Prevents browser caching of dynamic content)

bootstrap/
  └── app.php                   (Registered NoCacheMiddleware)

PWA_VERSION_SYSTEM.md           (Complete documentation)
```

### Modified Files
```
.env.example                    (Added APP_VERSION=1.0.0)
resources/views/partials/pwa.blade.php  (Version injection)
sw.js                           (Dynamic versioning)
```

---

## 🛠️ How to Trigger Updates in Future Deployments

**Every time you deploy new code:**

```bash
# 1. Increment version in .env
APP_VERSION=1.0.1  # Change from 1.0.0 → 1.0.1

# 2. Deploy code
git add -A && git commit -m "update: deploy version 1.0.1" && git push

# 3. Pull on server
ssh user@server && cd ~/public_html && git pull

# 4. Clear caches
php artisan config:cache && php artisan view:cache

# ✅ Done! All users auto-update within ~1-3 seconds
```

---

## 🔍 Verification

After deployment, verify everything works:

### **Open DevTools (F12)**

1. **Check App Version**
   - Console: `window.APP_VERSION` → should show new version
   - Should match your `.env` file

2. **Check Service Worker**
   - Application tab → Service Workers
   - Should show "activated and running"

3. **Check Caches**
   - Application tab → Cache Storage
   - Names should include new version (e.g., `YPMMH-static-1.0.1`)
   - Old caches should be gone

4. **Test Version Change**
   - Open in incognito window
   - Change `APP_VERSION` in `.env` to something higher
   - Reload prod page
   - Should see "Updating app..." banner
   - Page should reload automatically

---

## 🧪 Testing Guide

### **Local Testing (Windows XAMPP)**

```bash
# 1. Start MySQL and Apache
# Open XAMPP Control Panel and click "Start" on both

# 2. Navigate to app
cd c:\xampp\htdocs\app (1)

# 3. Update version in .env
# Change: APP_VERSION=1.0.0 → APP_VERSION=1.0.1

# 4. Clear config cache
php artisan config:cache

# 5. Open in browser
# http://localhost/app (1)/

# 6. Open DevTools (F12)
# Should see logs about version change

# 7. Verify caches cleared
# localStorage should be mostly empty
# Check console for messages: [PWA] Version cleared...
```

### **Manual Force Check (Any Browser)**

```javascript
// In browser console:
console.log(__PWA_DEBUG__)

// Force version check
await __PWA_DEBUG__.checkVersion()

// Force clear all data
await __PWA_DEBUG__.clearAll()

// Force reload
__PWA_DEBUG__.reload()
```

---

## ⚠️ Troubleshooting

### **Users Report Old Data Still Showing**

**Cause**: Version wasn't incremented or deployed

**Fix**:
```bash
# Verify APP_VERSION was changed
grep APP_VERSION ~/public_html/.env

# If not changed, update it:
echo "APP_VERSION=1.0.2" >> ~/public_html/.env

# Clear config cache
php artisan config:cache

# Users will auto-update on next page load
```

### **Users Stuck on Infinite Reload Loop**

**This is rare due to safety checks, but if it happens:**

1. **Server-side quick fix**: Rollback version in `.env`
   ```bash
   nano ~/public_html/.env
   # Change APP_VERSION to previous version
   ```

2. **User-side manual fix**: In browser console:
   ```javascript
   // Reset everything
   localStorage.clear()
   indexedDB.databases().then(dbs => 
     dbs.forEach(db => indexedDB.deleteDatabase(db.name))
   )
   location.reload()
   ```

### **Service Worker Not Activating**

Check DevTools → Application → Service Workers:
- Click "Skip Waiting" to force activation
- Or click "Unregister" and reload to re-register fresh

---

## 📞 Support

For issues or questions:

1. Check the full documentation: `PWA_VERSION_SYSTEM.md`
2. Check browser console for error messages (F12)
3. Check Application → Service Workers → Debug View
4. Review deployment logs for any errors

---

## ✅ Implementation Summary

| Feature | Status | Details |
|---------|--------|---------|
| Auto-detect version changes | ✅ | Runs on every pageload |
| Clear localStorage | ✅ | Preserves session data if needed |
| Clear IndexedDB | ✅ | All databases deleted |
| Clear Service Worker caches | ✅ | Old cache names removed |
| Prevent infinite reloads | ✅ | Max 3 reloads in 30s |
| Dynamic cache naming | ✅ | Uses APP_VERSION from .env |
| Push notifications | ✅ | Still fully functional |
| Offline support | ✅ | Maintained |
| Mobile support | ✅ | All devices supported |

---

## 🎉 Next Steps

1. **If this is deployed now**: Increment `APP_VERSION` in `.env` on next deployment
2. **If this is test environment**: Create a `.env` file with `APP_VERSION=1.0.0`
3. **Monitor logs**: Check that users aren't seeing unexpected reloads
4. **Document**: Let your team know about the new version system

---

## 📚 Related Files to Review

- [PWA Version System Documentation](./PWA_VERSION_SYSTEM.md) - Full technical guide
- [Laravel Config](./config/app.php) - Version configuration
- [Service Worker](./sw.js) - Updated SW with versioning
- [PWA Partial](./resources/views/partials/pwa.blade.php) - Version injection

---

**System Ready for Production! 🚀**

*Last Updated: April 3, 2026*
