<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('Admin.Settings.index', compact('settings'));
    }

    /**
     * Update branding settings (name, tagline, logo, favicon).
     */
    public function updateBranding(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:100',
            'app_tagline' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,ico,svg|max:512',
        ]);

        if ($request->filled('app_name')) {
            Setting::set('app_name', $request->app_name, 'branding');
        }

        if ($request->filled('app_tagline')) {
            Setting::set('app_tagline', $request->app_tagline, 'branding');
        }

        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('app_logo')->store('branding', 'public');
            Setting::set('app_logo', $path, 'branding');
        }

        if ($request->hasFile('app_favicon')) {
            // Delete old favicon if exists
            $oldFavicon = Setting::get('app_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $path = $request->file('app_favicon')->store('branding', 'public');
            Setting::set('app_favicon', $path, 'branding');
        }

        return back()->with('success', 'Branding settings updated successfully.');
    }

    /**
     * Update general settings (payment gateway, etc.).
     */
    public function update(Request $request)
    {
        $request->validate([
            'paystack_public_key' => 'nullable|string|max:255',
            'paystack_secret_key' => 'nullable|string|max:255',
            'paystack_merchant_email' => 'nullable|email|max:255',
            'paystack_payment_url' => 'nullable|url|max:255',
            // Contact & Social
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_address' => 'nullable|string|max:255',
            'office_hours_weekdays' => 'nullable|string|max:255',
            'office_hours_saturday' => 'nullable|string|max:255',
            'office_hours_sunday' => 'nullable|string|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            // Premium Subscription Settings
            'premium_price_monthly' => 'nullable|numeric|min:0',
            'premium_price_termly' => 'nullable|numeric|min:0',
            'premium_price_annually' => 'nullable|numeric|min:0',
            'premium_currency' => 'nullable|string|max:10',
            'trial_duration_days' => 'nullable|integer|min:0',
        ]);

        $keys = [
            'paystack_public_key',
            'paystack_secret_key',
            'paystack_merchant_email',
            'paystack_payment_url',
            // Contact & Social
            'contact_email',
            'contact_phone',
            'contact_whatsapp',
            'contact_address',
            'office_hours_weekdays',
            'office_hours_saturday',
            'office_hours_sunday',
            'social_facebook',
            'social_instagram',
            'social_twitter',
            'social_youtube',
            'social_linkedin',
            // Premium Subscription
            'premium_price_monthly',
            'premium_price_termly',
            'premium_price_annually',
            'premium_currency',
            'trial_duration_days',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                // Determine group based on key prefix or list
                $group = 'general';
                if (str_starts_with($key, 'paystack_'))
                    $group = 'payment';
                if (str_starts_with($key, 'contact_'))
                    $group = 'contact';
                if (str_starts_with($key, 'social_'))
                    $group = 'social';
                if (str_starts_with($key, 'office_'))
                    $group = 'contact';
                if (str_starts_with($key, 'premium_') || str_starts_with($key, 'trial_'))
                    $group = 'premium';

                Setting::set($key, $request->$key, $group);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Remove the app logo.
     */
    public function removeLogo()
    {
        $logo = Setting::get('app_logo');
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }

        Setting::where('key', 'app_logo')->delete();

        return back()->with('success', 'Logo removed successfully.');
    }

    /**
     * Remove the app favicon.
     */
    public function removeFavicon()
    {
        $favicon = Setting::get('app_favicon');
        if ($favicon && Storage::disk('public')->exists($favicon)) {
            Storage::disk('public')->delete($favicon);
        }

        Setting::where('key', 'app_favicon')->delete();

        return back()->with('success', 'Favicon removed successfully.');
    }
}
