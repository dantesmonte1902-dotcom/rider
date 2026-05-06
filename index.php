<?php
declare(strict_types=1);

/**
 * Front controller / router.
 *
 * Deployment:
 *   XAMPP:  http://localhost/rider/
 *   Hosting: configure DocumentRoot to point here, or use the provided .htaccess
 *
 * Routing strategy: Apache rewrite → all non-file requests land here.
 * See .htaccess for the rewrite rules.
 *
 * BASE_PATH is defined in app/config.php and defaults to '/rider'.
 * To deploy at domain root set BASE_PATH='' in config.php.
 */

require __DIR__ . '/app/config.php';
require __DIR__ . '/app/db.php';
require __DIR__ . '/app/helpers.php';

// ── Determine the request path ───────────────────────────────────────────────
$uri  = $_SERVER['REQUEST_URI'];
$path = (string) parse_url($uri, PHP_URL_PATH);

// Strip BASE_PATH prefix so routes are always relative (e.g. /admin/login)
if (BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) {
    $path = substr($path, strlen(BASE_PATH));
}

// Normalize trailing slash (except root /)
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}

if ($path === '') {
    $path = '/';
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// ── Routes ────────────────────────────────────────────────────────────────────
require __DIR__ . '/app/routes/admin_routes.php';
require __DIR__ . '/app/routes/public_routes.php';

// ── 404 fallback ─────────────────────────────────────────────────────────────
http_response_code(404);
view('partials/404');
