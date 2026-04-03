# PWA Version System - Quick Reference

## One-Minute Setup

### For Local Development (Windows XAMPP)

```bash
# 1. Verify .env exists and has:
APP_VERSION=1.0.0

# 2. Test version check
php artisan config:cache

# 3. Open in browser and check:
# - DevTools (F12) > Console
# - Should show: "[PWA] Version Check System Loading..."

# Done!
```

---

## One-Minute Deploy (Production)

```bash
# 1. Increment version in .env
APP_VERSION=1.0.1

# 2. Commit and push
git add .env && git commit -m "release: v1.0.1" && git push

# 3. Pull on server
ssh user@server
cd ~/public_html && git pull

# 4. Clear cache
php artisan config:cache

# ✅ All users auto-update!
```

---

## For Each Production Release

1. Change `APP_VERSION` number
2. Deploy code
3. Clear config cache
4. **Done** - users auto-update in 1-3 seconds

---

## Version Numbering (Semantic)

- **1.0.0** = Major.Minor.Patch
- `1.0.1` = Bug fix (patch)
- `1.1.0` = New feature (minor)
- `2.0.0` = Breaking change (major)

---

## Emergency Rollback

```bash
# If something breaks:
# 1. Revert APP_VERSION in .env to previous number
# 2. Run: php artisan config:cache
# 3. Users auto-downgrade on next page load
```

---

## User Experience

| Action | User Sees | Time |
|--------|-----------|------|
| Visit app (no update) | App loads normally | <100ms |
| Update available | "Updating app..." banner | 1-3 sec |
| After update | Fully refreshed app | All data fresh |

---

## Key Files

| File | Purpose | Edit When |
|------|---------|-----------|
| `.env` | Version configuration | Every release |
| `resources/views/partials/pwa.blade.php` | Version injection | Never (auto) |
| `sw.js` | Service worker logic | Never (auto) |
| `config/app.php` | Config definition | Never (auto) |

---

## Debugging (Console Commands)

```javascript
// Check current version
window.APP_VERSION

// Check stored version
localStorage.getItem('APP_VERSION')

// Force version check
await __PWA_DEBUG__.checkVersion()

// Force clear all data
await __PWA_DEBUG__.clearAll()

// View diagnostics
__PWA_DIAGNOSTICS__()
```

---

## Success Indicators

After updating APP_VERSION:
- [ ] Users see "Updating app..." briefly
- [ ] No infinite reloads
- [ ] Old data cleared from localStorage
- [ ] New version appears in DevTools
- [ ] Service Worker caches regenerated

---

## Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| Users stuck on old version | Did you increment APP_VERSION? |
| Infinite reload loop | System limits to 3 reloads max |
| Data still stale | Check localStorage was cleared |
| SW not updating | Check cache names in DevTools |
| Mobile app not updating | PWA auto-updates, may take 5 min |

---

## Support Docs

- Full Guide: `PWA_VERSION_SYSTEM.md`
- Deploy Steps: `DEPLOYMENT_GUIDE.md`
- This File: `QUICK_REFERENCE.md`

---

**That's it! The system handles the rest. 🚀**
