<?php
/*
 * Server-only contact form configuration.
 * Keep this file on the server. Do not expose it in client-side JavaScript.
 */
return [
    'recipient_email' => 'info@cleversloth.com',
    'from_email' => 'website@cleversloth.com',
    'from_name' => 'Clever Sloth LLC Website',
    'rate_limit_directory' => __DIR__ . '/.rate-limit',
    'turnstile_secret_key' => '', // Add a Cloudflare Turnstile secret key to require CAPTCHA verification.
    'minimum_form_seconds' => 3,
    'minimum_seconds_between_messages' => 90,
    'maximum_messages_per_hour' => 5,
];
