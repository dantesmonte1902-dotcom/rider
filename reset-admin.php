<?php
declare(strict_types=1);

/**
 * Admin Password Reset Utility
 * ─────────────────────────────────────────────────────────────────────────────
 * SECURITY: This script is restricted to localhost / 127.0.0.1 by default.
 *           On hosting you MUST set the APP_LOCAL=1 environment variable AND
 *           ensure the file is not publicly accessible (or delete it after use).
 *
 * Usage (XAMPP):
 *   http://localhost/rider/reset-admin.php?email=admin@example.com&pass=NewPass123!
 *
 * Delete this file after use on any non-local environment.
 */

require __DIR__ . '/app/config.php';
require __DIR__ . '/app/db.php';

header('Content-Type: text/plain; charset=utf-8');

// ── Guard: local-only ─────────────────────────────────────────────────────────
if (!IS_LOCAL) {
    http_response_code(403);
    echo "403 Forbidden\n";
    echo "This utility is restricted to localhost.\n";
    echo "Set APP_LOCAL=1 env var to enable on other hosts (use with caution).\n";
    exit;
}

// ── Parameters ────────────────────────────────────────────────────────────────
$email   = trim((string)($_GET['email'] ?? ''));
$newPass = (string)($_GET['pass'] ?? '');
$action  = strtolower(trim((string)($_GET['action'] ?? 'reset')));

// ── List mode: ?action=list ───────────────────────────────────────────────────
if ($action === 'list') {
    echo "=== Admin Users ===\n\n";
    try {
        $rows = db()->query('SELECT id, email, created_at FROM admin_users ORDER BY id ASC')
                    ->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            echo "(no admin users found)\n";
        } else {
            foreach ($rows as $r) {
                echo sprintf(" [%d] %s  (created: %s)\n", $r['id'], $r['email'], $r['created_at']);
            }
        }
    } catch (PDOException $e) {
        echo "DB error: " . $e->getMessage() . "\n";
    }
    echo "\nUsage to reset a password:\n";
    echo "  ?email=<email>&pass=<new_password>\n";
    exit;
}

// ── Create mode: ?action=create ───────────────────────────────────────────────
if ($action === 'create') {
    if ($email === '' || $newPass === '') {
        echo "Usage:\n";
        echo "  ?action=create&email=admin@example.com&pass=YourPassword123!\n";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: invalid email address.\n";
        exit;
    }

    if (strlen($newPass) < 6) {
        echo "Error: password must be at least 6 characters.\n";
        exit;
    }

    $hash = password_hash($newPass, PASSWORD_DEFAULT);

    try {
        $stmt = db()->prepare(
            'INSERT INTO admin_users (email, password_hash) VALUES (:email, :hash)'
        );
        $stmt->execute([':email' => $email, ':hash' => $hash]);
        echo "OK. Admin user created.\n";
        echo "Email: {$email}\n";
        echo "Login at: " . BASE_PATH . "/admin/login\n";
    } catch (PDOException $e) {
        if ((string)$e->getCode() === '23000') {
            echo "Error: email already exists. Use ?action=reset to update the password.\n";
        } else {
            echo "DB error: " . $e->getMessage() . "\n";
        }
    }
    exit;
}

// ── Reset mode (default) ──────────────────────────────────────────────────────
if ($email === '' || $newPass === '') {
    echo "Admin Password Reset Utility\n";
    echo "────────────────────────────\n\n";
    echo "Actions:\n\n";
    echo "  List admins:\n";
    echo "    ?action=list\n\n";
    echo "  Reset password:\n";
    echo "    ?email=admin@example.com&pass=NewPass123!\n\n";
    echo "  Create new admin:\n";
    echo "    ?action=create&email=admin@example.com&pass=NewPass123!\n\n";
    echo "DELETE THIS FILE AFTER USE on any non-local environment.\n";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: invalid email address.\n";
    exit;
}

if (strlen($newPass) < 6) {
    echo "Error: password must be at least 6 characters.\n";
    exit;
}

$hash = password_hash($newPass, PASSWORD_DEFAULT);

try {
    $stmt = db()->prepare('UPDATE admin_users SET password_hash = :hash WHERE email = :email');
    $stmt->execute([':hash' => $hash, ':email' => $email]);
} catch (PDOException $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit;
}

if ($stmt->rowCount() < 1) {
    echo "No rows updated. Email not found in admin_users.\n\n";
    echo "Existing admins:\n";
    try {
        $rows = db()->query('SELECT id, email FROM admin_users ORDER BY id ASC')
                    ->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            echo "  (none)\n";
        } else {
            foreach ($rows as $r) {
                echo "  [{$r['id']}] {$r['email']}\n";
            }
        }
        echo "\nTo create a new admin:\n";
        echo "  ?action=create&email={$email}&pass=" . urlencode($newPass) . "\n";
    } catch (PDOException $e) {
        echo "  (could not query: " . $e->getMessage() . ")\n";
    }
    exit;
}

echo "OK. Password updated for {$email}\n";
echo "Login at: " . BASE_PATH . "/admin/login\n";
echo "\nDELETE THIS FILE AFTER USE.\n";
