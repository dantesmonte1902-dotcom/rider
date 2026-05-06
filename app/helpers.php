<?php
declare(strict_types=1);

// ── CSRF ─────────────────────────────────────────────────────────────────────

/**
 * Generate (or return existing) CSRF token for the current session.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render a hidden CSRF input field.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

/**
 * Validate the CSRF token from a POST request.
 * Terminates with HTTP 419 on failure.
 */
function csrf_validate(): void
{
    $posted = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $posted)) {
        http_response_code(419);
        echo render_view('partials/error', [
            'title'   => 'CSRF Token Invalid',
            'message' => 'Security token mismatch. Please go back and try again.',
        ]);
        exit;
    }
}

// ── Redirect ─────────────────────────────────────────────────────────────────

/**
 * Redirect to a URL relative to BASE_PATH and exit.
 * Example: redirect('/admin/login') → Location: /rider/admin/login
 */
function redirect(string $path): never
{
    header('Location: ' . BASE_PATH . $path);
    exit;
}

// ── Flash messages ────────────────────────────────────────────────────────────

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): string
{
    $msg = $_SESSION['flash'][$key] ?? '';
    unset($_SESSION['flash'][$key]);
    return $msg;
}

// ── Views ─────────────────────────────────────────────────────────────────────

/**
 * Render a view file and return its output as a string.
 * @param string $view  e.g. 'admin/login' → views/admin/login.php
 * @param array  $data  Variables extracted into the view scope.
 */
function render_view(string $view, array $data = []): string
{
    $file = __DIR__ . '/../views/' . $view . '.php';
    if (!file_exists($file)) {
        return "<!-- View not found: {$view} -->";
    }
    extract($data, EXTR_SKIP);
    ob_start();
    require $file;
    return (string) ob_get_clean();
}

/**
 * Render a view directly to output (echoes it).
 */
function view(string $view, array $data = []): void
{
    echo render_view($view, $data);
}

// ── Auth ─────────────────────────────────────────────────────────────────────

/**
 * Require the admin to be logged in; redirect to login otherwise.
 */
function require_admin(): void
{
    if (empty($_SESSION['admin_id'])) {
        redirect('/admin/login');
    }
}

// ── HTML escaping ─────────────────────────────────────────────────────────────

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
