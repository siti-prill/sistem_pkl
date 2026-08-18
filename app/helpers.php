<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('logo_url')) {
    function logo_url(): string
    {
        $logo = setting('logo_path');

        return $logo ? asset('storage/'.$logo) : asset('logo-smk2.png');
    }
}
