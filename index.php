<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require __DIR__ . '/app/config.php';
require __DIR__ . '/app/db.php';
require __DIR__ . '/app/i18n.php';
require __DIR__ . '/app/security.php';
require __DIR__ . '/app/mail.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Trim base path (/rider) from URI so internal routes are /, /apply, /admin/login ...
if (defined('BASE_PATH') && BASE_PATH !== '' && strpos($path, BASE_PATH) === 0) {
    $path = substr($path, strlen(BASE_PATH));
    if ($path === '') $path = '/';
}

if ($path === '/') {
    render('home', []);
    exit;
}

if ($path === '/apply' && $method === 'GET') {
    ensure_csrf_token();
    render('apply', ['errors' => [], 'old' => []]);
    exit;
}

if ($path === '/apply' && $method === 'POST') {
    csrf_verify();

    $full_name = trim((string)($_POST['full_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $city = trim((string)($_POST['city'] ?? ''));
    $vehicle_type = trim((string)($_POST['vehicle_type'] ?? ''));
    $locale = current_locale();

    $errors = [];

    if ($full_name === '' || mb_strlen($full_name) < 3) $errors['full_name'] = t('err_name');
    if ($phone === '' || mb_strlen($phone) < 6) $errors['phone'] = t('err_phone');
    if ($city === '') $errors['city'] = t('err_city');
    if ($vehicle_type === '') $errors['vehicle_type'] = t('err_vehicle');

    if (!rate_limit_ok('apply:' . client_ip(), 5, 600)) {
        $errors['rate'] = t('err_rate_limit');
    }

    if ($errors) {
        ensure_csrf_token();
        render('apply', ['errors' => $errors, 'old' => $_POST]);
        exit;
    }

    $utm = read_utm();

    $stmt = db()->prepare("
        INSERT INTO applications (full_name, phone, city, vehicle_type, locale, utm_source, utm_medium, utm_campaign)
        VALUES (:full_name, :phone, :city, :vehicle_type, :locale, :utm_source, :utm_medium, :utm_campaign)
    ");
    $stmt->execute([
        ':full_name' => $full_name,
        ':phone' => $phone,
        ':city' => $city,
        ':vehicle_type' => $vehicle_type,
        ':locale' => $locale,
        ':utm_source' => $utm['utm_source'],
        ':utm_medium' => $utm['utm_medium'],
        ':utm_campaign' => $utm['utm_campaign'],
    ]);

    send_mail(
        APP_NOTIFY_EMAIL,
        t('mail_subject_admin'),
        render_to_string('emails/new_application', [
            'full_name' => $full_name,
            'phone' => $phone,
            'city' => $city,
            'vehicle_type' => $vehicle_type,
            'locale' => $locale,
        ])
    );

    header('Location: ' . BASE_PATH . '/apply/success');
    exit;
}

if ($path === '/apply/success') {
    render('apply_success', []);
    exit;
}

// Admin routes
require __DIR__ . '/app/admin_routes.php';

http_response_code(404);
echo "404";