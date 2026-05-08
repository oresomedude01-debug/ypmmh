<?php

// Quick diagnostic route - DELETE after checking
Route::get('/debug-mail-config', function () {
    return [
        'MAIL_MAILER' => env('MAIL_MAILER'),
        'MAIL_HOST' => env('MAIL_HOST'),
        'MAIL_PORT' => env('MAIL_PORT'),
        'MAIL_USERNAME' => env('MAIL_USERNAME') ? '***' : 'NOT SET',
        'MAIL_PASSWORD' => env('MAIL_PASSWORD') ? '***' : 'NOT SET',
        'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
        'config_default' => config('mail.default'),
        'config_from' => config('mail.from'),
    ];
});
