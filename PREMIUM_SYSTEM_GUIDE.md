# Premium Subscription Management System - Implementation Guide

## Overview

This document describes the new Premium Subscription Management System implemented for the Young Productive Muslim Mentoring Hub. The system includes:

1. **Admin Premium Users Dashboard** - View and manage all premium subscriptions
2. **Payment-Required Premium Subscription** - Ensure payment before premium access is granted
3. **Subscription Expiry Notifications** - Automated email and in-app notifications
4. **Auto-Renewal Feature** - Optional automatic renewal of subscriptions
5. **Admin Controls** - Extend, cancel, or reactivate subscriptions

---

## 1. Admin Premium Subscriptions Dashboard

### Access
- **Route**: `/admin/premiums`
- **Menu**: Admin Sidebar → Finance → Premium Subscriptions
- **Permission**: Admin role only

### Features

#### Statistics Cards
- **Total Premium Users**: Count of all users with any premium status
- **Active Subscriptions**: Users with active, non-expired subscriptions
- **Expiring Soon**: Users whose subscriptions expire within 7 days
- **Expired**: Users whose subscriptions have ended

#### Search & Filters
- **Search by Name/Email**: Find users quickly
- **Filter by Status**: Active, Expiring Soon, Expired, Trial
- **Filter by Plan**: Monthly, Termly (4 months), Annually
- **Export Report**: Download subscription data

#### User Information Displayed
| Column | Description |
|--------|-------------|
| User Name | Profile picture and user name/username |
| Email | User's email address |
| Role | User role (Child, Parent, Mentor) |
| Plan | Subscription plan type |
| Status | Current subscription status with color badge |
| Expires On | Expiration date and time |
| Days Remaining | Days left before expiration (highlighted if urgent) |
| Auto-Renewal | Enabled/Disabled status |
| Actions | View, Extend, Cancel, Reactivate (context-aware) |

#### Admin Actions

**For Active Subscriptions:**
- **View User**: Opens user profile
- **Extend**: Add one month to subscription
- **Cancel**: End subscription immediately (disables auto-renewal)

**For Expired Subscriptions:**
- **Reactivate**: Restore subscription for one month from reactivation date

### Status Indicators
- 🟢 **Active**: Subscription is valid and not expiring soon
- 🟡 **Expiring Soon**: Less than 7 days until expiration (animated)
- 🔴 **Expired**: Subscription has ended
- 🔵 **Trial**: User is on trial period

---

## 2. Payment-Required Premium Subscription Flow

### Updated Behavior

**Before**: If price was set to 0, premium would be granted without payment
**After**: ALL premium subscriptions now require successful payment verification

### Payment Flow
1. User clicks "Premium Upgrade" or "Premium Subscriptions"
2. User selects plan (Monthly, Termly, Annually)
3. System creates a pending payment record
4. User is redirected to Paystack payment gateway
5. Upon successful payment, premium status is activated
6. Auto-renewal is enabled by default
7. Success page displays subscription details

### Important Notes
- ⚠️ If no pricing is configured, users see: "Premium pricing is not configured"
- Payment gateway (Paystack) must be configured in Settings
- Free tier has been removed - all premium access requires payment

---

## 3. Subscription Expiry Notification System

### Automated Notifications

The system sends automated notifications via **daily scheduler**:

```bash
php artisan premium:check-subscriptions
```

#### When Notifications Are Sent

**Expiring Soon (3 days before expiration)**
- Sends email & in-app notification
- Only once per 24 hours (to avoid spam)
- Reminds user to renew

**Expired (when subscription ends)**
- Sends email & in-app notification
- Updates status to 'expired'
- Disables auto-renewal (if enabled)
- Only sent if auto-renewal didn't succeed

**Auto-Renewal Initiated**
- Sends notification when auto-renewal charge is processed
- Includes transaction details
- Links to premium dashboard

### Email Notifications

#### Subscription Expiring Soon Email
```
Subject: ⏰ Your Premium Subscription is Expiring Soon

Content Includes:
- Days remaining
- Expiration date
- Renewal link
- Auto-renewal status
- Support contact info
```

#### Subscription Expired Email
```
Subject: 💔 Your Premium Subscription Has Expired

Content Includes:
- Expiration date
- Lost benefits
- Renewal link
- Call-to-action
- What's included in renewal
```

#### Auto-Renewal Initiated Email
```
Subject: 🔄 Auto-Renewal Initiated for Your Premium Subscription

Content Includes:
- Plan name
- Amount charged
- Transaction ID
- Dashboard link
```

### In-App Notifications

Notifications appear in the "My Alerts" section with:
- Clear title and message
- Remaining days or status
- "Renew Now" or "View Details" button
- Color-coded badges (Warning/Danger/Info)

### Database Storage

All notifications are stored in `notifications` table with:
- User ID
- Notification type
- Full message data
- Read/Unread status
- Timestamp

---

## 4. Auto-Renewal Feature

### How Auto-Renewal Works

1. **Enabled by Default**: When user purchases premium, auto-renewal is enabled
2. **Scheduled Check**: Daily scheduler checks for expired subscriptions with auto-renewal enabled
3. **Renewal Attempt**: System attempts to renew using stored payment method
4. **Notification Sent**: User is notified of renewal attempt
5. **Expiration Check**: Next day, if renewal failed, user is notified

### User Controls

#### Toggle Auto-Renewal
```javascript
// Endpoint: POST /premium/toggle-auto-renewal
// Payload: { child_id: optional (for parents) }
```

**Response:**
```json
{
  "success": true,
  "message": "Auto-renewal has been enabled/disabled",
  "auto_renewal_enabled": true/false
}
```

#### When Auto-Renewal Can Be Toggled
- ✅ Only when subscription is ACTIVE
- ✅ Parents can toggle for their children
- ✅ Children can toggle their own
- ❌ Cannot toggle for expired subscriptions
- ❌ Cannot toggle for trial subscriptions

### Implementation Details

**Model Updates:**
- Added `auto_renewal_enabled` column (boolean, default: false → true after purchase)
- Added `last_premium_notification_sent_at` column (timestamp)

**Database Migration:**
```bash
php artisan migrate
```

---

## 5. Scheduler Configuration

### Daily Premium Check

The scheduler runs this command daily:

```php
// routes/console.php
Schedule::command('premium:check-subscriptions')->daily();
```

### What It Does (Daily)

1. **Find Subscriptions Expiring in 3 Days**
   - Check subscriptions within [now() - now() + 3 days]
   - Skip if notification sent in last 24 hours
   - Send notification

2. **Find Expired Subscriptions**
   - Check subscriptions with end date in the past
   - Mark status as 'expired'
   - Disable auto-renewal
   - Send expiry notification

3. **Attempt Auto-Renewals**
   - Find expired subscriptions with auto-renewal enabled
   - Process max 10 per run
   - Create payment records
   - Send renewal initiated notification

### Scheduler Artisan Command

```bash
# Run the check manually (for testing)
php artisan premium:check-subscriptions

# Schedule checker (needs to run via cron/scheduler)
# Add to server crontab:
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Database Schema Changes

### New Columns in `users` Table

```sql
-- Auto-renewal setting
alter table users add column auto_renewal_enabled boolean default false after premium_plan;

-- Track last notification sent
alter table users add column last_premium_notification_sent_at timestamp nullable after auto_renewal_enabled;
```

### Affected Tables

- `users` - Added auto-renewal columns
- `payments` - No changes (existing structure used)
- `notifications` - Uses existing Laravel notifications table

---

## 7. Updated Controllers

### PremiumSubscriptionController

**New/Updated Methods:**

```php
// Always require payment (no free tier)
public function checkout(Request $request)

// Toggle auto-renewal for authenticated user
public function toggleAutoRenewal(Request $request)

// Updated to enable auto-renewal by default
private function grantPremium(User $child, string $plan)
```

### PremiumController (Admin)

**New Controller for Admin Management:**

```php
// Admin\PremiumController

public function index(Request $request)           // List all premium users
public function extend(User $user)                // Extend by 1 month
public function cancel(User $user)                // Cancel subscription
public function reactivate(User $user)            // Reactivate expired
```

---

## 8. Routes Added

### Authenticated User Routes
```php
POST  /premium/toggle-auto-renewal      // Toggle auto-renewal
```

### Admin Routes
```php
GET   /admin/premiums                   // View all premium users
POST  /admin/premiums/{user}/extend     // Extend subscription
POST  /admin/premiums/{user}/reactivate // Reactivate expired
DELETE /admin/premiums/{user}/cancel    // Cancel subscription
```

---

## 9. New Notification Classes

### SubscriptionExpiringNotification
- **Sends**: 3 days before expiration
- **Channels**: Email + Database
- **Queue**: Yes (asynchronous)

### SubscriptionExpiredNotification
- **Sends**: When subscription ends
- **Channels**: Email + Database
- **Queue**: Yes (asynchronous)

### SubscriptionRenewalPendingNotification
- **Sends**: When auto-renewal initiates
- **Channels**: Email + Database
- **Queue**: Yes (asynchronous)

---

## 10. New Artisan Command

### CheckPremiumSubscriptions

**File**: `app/Console/Commands/CheckPremiumSubscriptions.php`

**Description**: Checks subscriptions daily and sends notifications

**Usage**:
```bash
php artisan premium:check-subscriptions
```

**What It Checks**:
- Subscriptions expiring within 3 days
- Expired subscriptions
- Auto-renewal candidates

---

## 11. Configuration Requirements

### Settings That Must Be Configured

1. **Premium Pricing** (Admin → Settings):
   - `premium_price_monthly` - Monthly plan price
   - `premium_price_termly` - Termly (4 month) plan price
   - `premium_price_annually` - Annual plan price
   - `premium_currency` - Currency code (e.g., NGN)

2. **Paystack Configuration** (Admin → Settings):
   - `paystack_public_key` - Public key from Paystack
   - `paystack_secret_key` - Secret key from Paystack

3. **Mail Configuration** (.env file):
   - `MAIL_MAILER=smtp`
   - `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
   - `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`

---

## 12. Testing the System

### Manual Testing Steps

1. **Admin Dashboard**
   - Navigate to Admin → Finance → Premium Subscriptions
   - Verify stats display correctly
   - Test search and filters
   - Click actions (extend, cancel, reactivate)

2. **Premium Purchase**
   - Go to Premium Upgrade (as Child or Parent)
   - Select a plan
   - Verify it redirects to Paystack
   - Complete test payment
   - Verify success page and auto-renewal is enabled

3. **Auto-Renewal Toggle**
   - After purchasing, use browser console:
   ```javascript
   fetch('/premium/toggle-auto-renewal', {
     method: 'POST',
     headers: {'X-CSRF-TOKEN': csrf_token},
     body: JSON.stringify({})
   })
   ```
   - Verify toggle works

4. **Notifications**
   - Run command manually:
   ```bash
   php artisan premium:check-subscriptions
   ```
   - Check user's notifications page
   - Check email inbox

---

## 13. Troubleshooting

### No Notifications Sent

**Check**:
1. Is scheduler running? `php artisan schedule:list`
2. Are email settings configured? `php artisan mail:test`
3. User subscription end date correct? Check database
4. Last notification timestamp? Check `last_premium_notification_sent_at`

**Fix**:
```bash
# Test notifications manually
php artisan premium:check-subscriptions

# Check email configuration
php artisan config:show mail

# Clear cache if settings changed
php artisan config:clear
```

### Auto-Renewal Not Working

**Check**:
1. Is `auto_renewal_enabled = true`? Database check
2. Has payment been fully processed? Check `payments` table status
3. Paystack credentials configured? Admin settings

**Fix**:
```bash
# Run check command
php artisan premium:check-subscriptions

# Check payment gateway logs
tail -f storage/logs/laravel.log
```

---

## 14. Future Enhancements

Potential improvements:

1. **Paystack Recurring Charges**: Automate payment processing
2. **Multiple Payment Methods**: Add Stripe, Flutterwave, etc.
3. **Subscription Analytics**: Dashboard showing revenue trends
4. **Proration**: Charge difference if upgrading mid-cycle
5. **Invoice Management**: Generate and track invoices
6. **Discount Codes**: Support promotional codes
7. **Dunning Management**: Retry failed payments with user notification
8. **Subscription Tiers**: Multiple tier levels with different features

---

## Summary

The Premium Subscription Management System is now fully implemented with:

✅ Admin dashboard for managing subscriptions
✅ Payment-required checkout flow
✅ Automated expiry notifications (email + in-app)
✅ Auto-renewal with user controls
✅ Daily scheduler for maintenance tasks
✅ Comprehensive admin actions
✅ Database schema updates
✅ New Artisan command
✅ Three notification classes

**Next Step**: Run migrations and configure premium pricing and Paystack credentials in Admin Settings.
