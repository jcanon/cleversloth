# SiteGround FTP deployment

## What to upload

Run npm run build, then upload the contents of deploy/ to the web root for cleversloth.com (commonly public_html/). Upload the contents, not the outer deploy directory, so the site opens at the domain root.

The deploy folder is self-contained: it contains the Vue application, readable compiled JavaScript and CSS, images, icons, fonts, legal-policy routes, the PHP contact endpoint, and the Apache configuration files.

## Before going live

1. In deploy/api/config.php, confirm recipient_email and set from_email to a real mailbox at cleversloth.com. Create that mailbox in SiteGround first.
2. Create deploy/api/.rate-limit/ in File Manager or let PHP create it on the first request. If it cannot write there, set that directory to the lowest permissions SiteGround allows PHP to write (usually 0755; some accounts require 0775).
3. Send a real test inquiry after upload. Check that it arrives, that its Reply-To address works, and that an immediate second submission is rate-limited.

## Contact form protections

The form does not rely on front-end checks alone. The PHP endpoint validates the method and content type, issues a session CSRF token, rejects forged tokens, validates required values and lengths, blocks header-injection line breaks, silently catches honeypot bots, requires a minimum completion time, limits each IP address to one message every 90 seconds and five per hour, and uses a fixed mail subject and From header. Turnstile support is already wired in but remains disabled until keys are added.

No form can guarantee zero spam, particularly if a human completes it. This layered setup sharply reduces automated abuse while keeping the inquiry experience friendly.

## Local development

Run these commands from this directory: npm install, npm run dev, and npm run build.

Use PHP 8.1 or newer for api/contact.php. SiteGround’s current PHP versions meet that requirement; select PHP 8.1+ in Site Tools if needed.

