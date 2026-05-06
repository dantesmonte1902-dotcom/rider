<?php
declare(strict_types=1);

function ensure_csrf_token(): void {
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(16));
    }
}

function csrf_token(): string {
    ensure_csrf_token();
    return (string)$_SESSION['_csrf'];
}

function csrf_verify(): void {
    $token = (string)($_POST['_csrf'] ?? '');
    if ($token === '' || !hash_equals((string)($_SESSION['_csrf'] ?? ''), $token)) {
        http_response_code(419);
        echo "CSRF token invalid";
        exit;
    }
}

function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// In-memory session rate limit (demo). For production use Redis.
function rate_limit_ok(string $key, int $max, int $windowSeconds): bool {
    $now = time();
    $_SESSION['_rl'] ??= [];
    $_SESSION['_rl'][$key] ??= [];

    $_SESSION['_rl'][$key] = array_values(array_filter(
        $_SESSION['_rl'][$key],
        fn($ts) => is_int($ts) && ($now - $ts) < $windowSeconds
    ));

    if (count($_SESSION['_rl'][$key]) >= $max) return false;

    $_SESSION['_rl'][$key][] = $now;
    return true;
}

function read_utm(): array {
    $keys = ['utm_source', 'utm_medium', 'utm_campaign'];
    $utm = [];
    foreach ($keys as $k) {
        if (isset($_GET[$k]) && is_string($_GET[$k]) && $_GET[$k] !== '') {
            $_SESSION[$k] = $_GET[$k];
        }
        $utm[$k] = $_SESSION[$k] ?? null;
    }
    return $utm;
}