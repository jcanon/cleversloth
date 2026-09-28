<?php
declare(strict_types=1);

/* A small SMTP client for shared hosting. Credentials stay in config.php. */

function smtpReadResponse($socket): array {
    $lines = [];
    $code = 0;
    while (($line = fgets($socket, 515)) !== false) {
        $lines[] = rtrim($line, "\r\n");
        if (preg_match('/^(\d{3})([ -])/', $line, $match)) {
            $code = (int) $match[1];
            if ($match[2] === ' ') break;
        } else {
            break;
        }
    }
    return [$code, implode("\n", $lines)];
}

function smtpCommand($socket, string $command, array $expected, string $stage): bool {
    if (fwrite($socket, $command . "\r\n") === false) {
        error_log("Clever Sloth SMTP {$stage} write failed.");
        return false;
    }
    [$code] = smtpReadResponse($socket);
    if (!in_array($code, $expected, true)) {
        error_log("Clever Sloth SMTP {$stage} failed with response code {$code}.");
        return false;
    }
    return true;
}

function smtpSend(array $config, string $recipient, string $replyTo, string $subject, string $body): bool {
    $host = trim((string) ($config['smtp_host'] ?? ''));
    $username = trim((string) ($config['smtp_username'] ?? ''));
    $password = (string) ($config['smtp_password'] ?? '');
    $port = (int) ($config['smtp_port'] ?? 465);
    $encryption = (string) ($config['smtp_encryption'] ?? 'ssl');

    if ($host === '' || $username === '' || $password === '' || $port < 1 || $port > 65535) {
        error_log('Clever Sloth SMTP configuration is incomplete.');
        return false;
    }

    $endpoint = ($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
    $socket = @stream_socket_client($endpoint, $errorNumber, $errorMessage, 15, STREAM_CLIENT_CONNECT);
    if ($socket === false) {
        error_log("Clever Sloth SMTP connection failed ({$errorNumber}).");
        return false;
    }
    stream_set_timeout($socket, 15);

    [$greeting] = smtpReadResponse($socket);
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    if ($greeting !== 220) {
        error_log("Clever Sloth SMTP greeting failed with response code {$greeting}.");
    }
    $success = $greeting === 220
        && smtpCommand($socket, 'EHLO ' . preg_replace('/[^A-Za-z0-9.-]/', '', $domain), [250], 'EHLO');

    if ($success && $encryption === 'tls') {
        $success = smtpCommand($socket, 'STARTTLS', [220], 'STARTTLS')
            && stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)
            && smtpCommand($socket, 'EHLO ' . preg_replace('/[^A-Za-z0-9.-]/', '', $domain), [250], 'EHLO after TLS');
        if (!$success) error_log('Clever Sloth SMTP TLS negotiation failed.');
    }
    if ($success) {
        $success = smtpCommand($socket, 'AUTH LOGIN', [334], 'AUTH')
            && smtpCommand($socket, base64_encode($username), [334], 'username')
            && smtpCommand($socket, base64_encode($password), [235], 'password');
    }
    if ($success) {
        $success = smtpCommand($socket, 'MAIL FROM:<' . $config['from_email'] . '>', [250], 'sender')
            && smtpCommand($socket, 'RCPT TO:<' . $recipient . '>', [250, 251], 'recipient')
            && smtpCommand($socket, 'DATA', [354], 'DATA');
    }
    if ($success) {
        $messageIdDomain = preg_replace('/^.*@/', '', (string) $config['from_email']);
        $messageId = bin2hex(random_bytes(16)) . '@' . $messageIdDomain;
        // SMTP requires CRLF line endings. Normalizing here keeps plain-text
        // inquiry fields from being collapsed into one line by mail clients.
        $normalizedBody = preg_replace("~\r\n?|\n~", "\r\n", $body);
        $normalizedBody = preg_replace('/(^|\r\n)\./', '$1..', $normalizedBody ?? '');
        $message = 'Date: ' . date(DATE_RFC2822) . "\r\n"
            . "Message-ID: <{$messageId}>\r\n"
            . "From: {$config['from_name']} <{$config['from_email']}>\r\n"
            . "Reply-To: {$replyTo}\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Subject: {$subject}\r\n\r\n"
            . $normalizedBody;
        $success = fwrite($socket, $message . "\r\n.\r\n") !== false;
        if ($success) [$code] = smtpReadResponse($socket);
        $success = $success && $code === 250;
        if (!$success) error_log("Clever Sloth SMTP message body failed with response code {$code}.");
    }
    @smtpCommand($socket, 'QUIT', [221], 'QUIT');
    fclose($socket);

    if ($success) {
        error_log('Clever Sloth SMTP accepted the contact-form message for delivery.');
    } else {
        error_log('Clever Sloth SMTP mail delivery failed.');
    }
    return $success;
}
