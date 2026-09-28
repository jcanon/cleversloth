<?php
/*
 * Server-only contact form configuration.
 * Keep this file on the server. Do not expose it in client-side JavaScript.
 */
return [
    'recipient_email' => 'info@cleversloth.com',
    'from_email' => 'info@cleversloth.com',
    'from_name' => 'Clever Sloth LLC Website',
    /*
     * Authenticated SMTP is required because this SiteGround account rejects
     * PHP's built-in mail() transport. Copy the exact outgoing-server details
     * from Site Tools > Email > Accounts > Mail Configuration. Keep the
     * password only in this server-side file; never add it to Vue or Git.
     */
    'mail_transport' => 'smtp',
    'smtp_host' => '',
    'smtp_port' => 465,
    'smtp_encryption' => 'ssl', // Use 'ssl' for implicit TLS (465) or 'tls' for STARTTLS (587).
    'smtp_username' => 'info@cleversloth.com',
    'smtp_password' => '',
    'rate_limit_directory' => __DIR__ . '/.rate-limit',
    'turnstile_secret_key' => '', // Add a Cloudflare Turnstile secret key to require CAPTCHA verification.
    'minimum_form_seconds' => 3,
    'minimum_seconds_between_messages' => 90,
    'maximum_messages_per_hour' => 5,
];
