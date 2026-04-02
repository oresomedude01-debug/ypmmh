<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('Admin.Settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'paystack_public_key' => 'nullable|string',
            'paystack_secret_key' => 'nullable|string',
            'paystack_payment_url' => 'nullable|url',
            'paystack_merchant_email' => 'nullable|email',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'payment');
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:100',
            'app_tagline' => 'nullable|string|max:200',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,ico,svg|max:512',
        ]);

        // Update app name
        if ($request->filled('app_name')) {
            Setting::set('app_name', $request->app_name, 'branding');
        }

        // Update tagline
        if ($request->filled('app_tagline')) {
            Setting::set('app_tagline', $request->app_tagline, 'branding');
        }

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('app_logo')->store('branding', 'public');
            Setting::set('app_logo', $logoPath, 'branding');
        }

        // Handle favicon upload
        if ($request->hasFile('app_favicon')) {
            // Delete old favicon if exists
            $oldFavicon = Setting::get('app_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            // Store new favicon
            $faviconPath = $request->file('app_favicon')->store('branding', 'public');
            Setting::set('app_favicon', $faviconPath, 'branding');
        }

        return redirect()->back()->with('success', 'Branding settings updated successfully.');
    }

    public function removeLogo()
    {
        $logo = Setting::get('app_logo');
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }
        Setting::where('key', 'app_logo')->delete();

        return redirect()->back()->with('success', 'Logo removed successfully.');
    }

    public function removeFavicon()
    {
        $favicon = Setting::get('app_favicon');
        if ($favicon && Storage::disk('public')->exists($favicon)) {
            Storage::disk('public')->delete($favicon);
        }
        Setting::where('key', 'app_favicon')->delete();

        return redirect()->back()->with('success', 'Favicon removed successfully.');
    }
}
