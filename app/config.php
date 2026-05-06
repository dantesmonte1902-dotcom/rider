<?php
declare(strict_types=1);

// ── Base path ────────────────────────────────────────────────────────────────
// Adjust BASE_PATH if you deploy the app at a different sub-directory.
// For XAMPP at http://localhost/rider/ this should be '/rider'.
// For a domain root (http://example.com/) use ''.
define('BASE_PATH', '/rider');

// ── Database ─────────────────────────────────────────────────────────────────
// Override via environment variables for production / hosting.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'deneme');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// ── Environment detection ─────────────────────────────────────────────────────
// IS_LOCAL is true when running on localhost/127.0.0.1 OR when the
// APP_LOCAL env var is explicitly set to '1'.
$_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
define('IS_LOCAL',
    getenv('APP_LOCAL') === '1' ||
    in_array(strtok($_host, ':'), ['localhost', '127.0.0.1', '::1'], true)
);

// ── Session ───────────────────────────────────────────────────────────────────
// Start the session once here so every route has it available.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => BASE_PATH . '/',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
