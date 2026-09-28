<?php
declare(strict_types=1);

/*
 * Lightweight contact endpoint for SiteGround PHP hosting.
 *
 * Protections:
 * - same-origin form requests and per-session CSRF token
 * - invisible honeypot and minimum completion time
 * - per-IP cooldown plus hourly rate limit
 * - strict length, format, and newline validation
 * - optional Cloudflare Turnstile verification
 *
 * SiteGround deployment: ensure api/.rate-limit is writable by PHP (0755 or
 * 0775 depending on the account). Do not put this endpoint behind a cache.
 */

session_set_cookie_params([
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

$config = require __DIR__ . '/config.php';
require_once __DIR__ . '/smtp.php';

function respond(int $status, array $body): never {
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit;
}

function csrfToken(): string {
    if (empty($_SESSION['contact_csrf'])) {
        $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['contact_csrf'];
}

function clientIp(): string {
    // Do not trust forwarded headers unless you explicitly configure a trusted proxy.
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function validateString(mixed $value, int $maximum, bool $required = false): string {
    if (!is_string($value)) {
        if ($required) throw new InvalidArgumentException('Please complete all required fields.');
        return '';
    }
    $value = trim($value);
    if (preg_match('/[\r\n]/', $value)) {
        throw new InvalidArgumentException('Please remove line breaks from this field.');
    }
    if ($required && $value === '') throw new InvalidArgumentException('Please complete all required fields.');
    if (mb_strlen($value) > $maximum) throw new InvalidArgumentException('One of the fields is too long.');
    return $value;
}

function verifyTurnstile(string $token, string $secret, string $ip): bool {
    if ($secret === '') return true;
    if ($token === '') return false;
    $payload = http_build_query(['secret' => $secret, 'response' => $token, 'remoteip' => $ip]);
    $context = stream_context_create(['http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($payload),
        'content' => $payload,
        'timeout' => 10,
    ]]);
    $result = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
    $body = is_string($result) ? json_decode($result, true) : null;
    return is_array($body) && ($body['success'] ?? false) === true;
}

function enforceRateLimit(string $ip, array $config): void {
    $directory = $config['rate_limit_directory'];
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        respond(503, ['ok' => false, 'message' => 'The form is temporarily unavailable. Please email us directly.']);
    }
    $file = $directory . '/' . hash('sha256', $ip) . '.json';
    $now = time();
    $handle = fopen($file, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        respond(503, ['ok' => false, 'message' => 'The form is temporarily unavailable. Please try again later.']);
    }
    $existing = stream_get_contents($handle);
    $timestamps = is_string($existing) ? json_decode($existing, true) : [];
    $timestamps = is_array($timestamps) ? array_values(array_filter($timestamps, fn($time) => is_int($time) && $time > $now - 3600)) : [];
    $lastMessage = end($timestamps);
    if ($lastMessage && $lastMessage > $now - $config['minimum_seconds_between_messages']) {
        flock($handle, LOCK_UN); fclose($handle);
        respond(429, ['ok' => false, 'message' => 'Please wait a little before sending another message.']);
    }
    if (count($timestamps) >= $config['maximum_messages_per_hour']) {
        flock($handle, LOCK_UN); fclose($handle);
        respond(429, ['ok' => false, 'message' => 'This form has reached its hourly message limit. Please try again later.']);
    }
    $timestamps[] = $now;
    rewind($handle); ftruncate($handle, 0); fwrite($handle, json_encode($timestamps)); fflush($handle);
    flock($handle, LOCK_UN); fclose($handle);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    respond(200, ['ok' => true, 'csrfToken' => csrfToken()]);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['ok' => false, 'message' => 'Method not allowed.']);
}
$contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '', 2)[0]));
if (!in_array($contentType, ['application/json', 'application/x-www-form-urlencoded'], true)) {
    respond(415, ['ok' => false, 'message' => 'Unsupported request type.']);
}
$input = $contentType === 'application/json'
    ? json_decode(file_get_contents('php://input'), true)
    : $_POST;
if (!is_array($input)) respond(400, ['ok' => false, 'message' => 'Invalid form data.']);
// Accept a header for backward-compatible JSON submissions and a body token for standard forms.
$submittedCsrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($input['csrfToken'] ?? '');
if (!is_string($submittedCsrf) || !hash_equals(csrfToken(), $submittedCsrf)) {
    respond(403, ['ok' => false, 'message' => 'Your form session expired. Refresh the page and try again.']);
}
if (($input['website'] ?? '') !== '') respond(200, ['ok' => true]); // Honeypot: accept silently.
$startedAt = filter_var($input['formStartedAt'] ?? null, FILTER_VALIDATE_INT);
if (!$startedAt || (microtime(true) * 1000 - $startedAt) < ($config['minimum_form_seconds'] * 1000)) {
    respond(400, ['ok' => false, 'message' => 'Please take a moment to complete the form.']);
}

try {
    $name = validateString($input['name'] ?? null, 100, true);
    $email = validateString($input['email'] ?? null, 254, true);
    $company = validateString($input['company'] ?? null, 150);
    $phone = validateString($input['phone'] ?? null, 40);
    $projectType = validateString($input['projectType'] ?? null, 100, true);
    $message = is_string($input['message'] ?? null) ? trim($input['message']) : '';
    if ($message === '' || mb_strlen($message) > 3000) throw new InvalidArgumentException('Please provide a short project description.');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('Please enter a valid email address.');
    if (($input['privacyAccepted'] ?? false) !== true && ($input['privacyAccepted'] ?? '') !== 'true') {
        throw new InvalidArgumentException('Please agree to the Privacy Policy before sending.');
    }
} catch (InvalidArgumentException $exception) {
    respond(422, ['ok' => false, 'message' => $exception->getMessage()]);
}

$ip = clientIp();
if (!verifyTurnstile((string)($input['turnstileToken'] ?? ''), $config['turnstile_secret_key'], $ip)) {
    respond(403, ['ok' => false, 'message' => 'The security check did not pass. Please try again.']);
}
enforceRateLimit($ip, $config);

$body = "New Clever Sloth LLC website inquiry\n\n"
    . "Name: {$name}\nEmail: {$email}\nCompany: {$company}\nPhone: {$phone}\n"
    . "Service: {$projectType}\n\nMessage:\n{$message}\n";
$sent = ($config['mail_transport'] ?? '') === 'smtp'
    ? smtpSend($config, $config['recipient_email'], $email, 'New website inquiry', $body)
    : mail($config['recipient_email'], 'New website inquiry', $body, implode("\r\n", [
        'From: ' . $config['from_name'] . ' <' . $config['from_email'] . '>',
        'Reply-To: ' . $email,
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
    ]));

if (!$sent) {
    error_log('Clever Sloth contact form mail delivery failed.');
    respond(503, ['ok' => false, 'message' => 'Your message could not be sent. Please email info@cleversloth.com.']);
}

$_SESSION['contact_csrf'] = bin2hex(random_bytes(32)); // One-time token after successful submission.
respond(200, ['ok' => true]);
