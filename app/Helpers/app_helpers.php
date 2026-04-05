<?php

use App\Models\Setting;

if (!function_exists('app_setting')) {
    /**
     * Get an application setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function app_setting($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('app_logo')) {
    /**
     * Get the application logo URL.
     *
     * @return string
     */
    function app_logo()
    {
        $logo = Setting::get('app_logo');
        if ($logo) {
            return asset('storage/' . $logo);
        }
        // Default logo
        return asset('ypmh_logo-removebg-preview.png');
    }
}

if (!function_exists('app_favicon')) {
    /**
     * Get the application favicon URL.
     *
     * @return string
     */
    function app_favicon()
    {
        $favicon = Setting::get('app_favicon');
        if ($favicon) {
            return asset('storage/' . $favicon);
        }
        // Default favicon
        return asset('ypmh_logo-removebg-preview.png');
    }
}

if (!function_exists('app_name')) {
    /**
     * Get the application name.
     *
     * @return string
     */
    function app_name()
    {
        return Setting::get('app_name', config('app.name', 'YPMMH'));
    }
}

if (!function_exists('app_tagline')) {
    /**
     * Get the application tagline.
     *
     * @return string
     */
    function app_tagline()
    {
        return Setting::get('app_tagline', 'Young Productive Muslim Mentoring Hub');
    }
}
