<?php
declare(strict_types=1);

// ── Public routes ─────────────────────────────────────────────────────────────

// GET / — home/landing page
if ($path === '/' && $method === 'GET') {
    view('public/home');
    exit;
}

// GET /lang/{code} — language switcher (stores preference, redirects back)
if (preg_match('#^/lang/(bs|en|tr|ar)$#', $path, $lm) && $method === 'GET') {
    $_SESSION['lang'] = $lm[1];
    $back = $_SERVER['HTTP_REFERER'] ?? (BASE_PATH . '/');
    header('Location: ' . $back);
    exit;
}

// GET /privacy
if ($path === '/privacy' && $method === 'GET') {
    view('public/privacy');
    exit;
}

// GET /terms
if ($path === '/terms' && $method === 'GET') {
    view('public/terms');
    exit;
}

// GET /apply
if ($path === '/apply' && $method === 'GET') {
    try {
        $cities       = db()->query('SELECT id, name FROM cities ORDER BY name ASC')->fetchAll();
        $vehicle_types = db()->query('SELECT id, name FROM vehicle_types ORDER BY name ASC')->fetchAll();
    } catch (PDOException $e) {
        $cities       = [];
        $vehicle_types = [];
    }

    view('public/apply', [
        'cities'        => $cities,
        'vehicle_types' => $vehicle_types,
        'error'         => flash_get('apply_error'),
    ]);
    exit;
}

// POST /apply
if ($path === '/apply' && $method === 'POST') {
    csrf_validate();

    // Honeypot check — bots fill hidden fields; humans leave them empty
    if (($_POST['website'] ?? '') !== '') {
        // Silently discard bot submission and redirect away without confirming success
        redirect('/');
    }

    $name          = trim((string)($_POST['name'] ?? ''));
    $email         = trim((string)($_POST['email'] ?? ''));
    $phone         = trim((string)($_POST['phone'] ?? ''));
    $city          = trim((string)($_POST['city'] ?? ''));
    $vehicle_type  = trim((string)($_POST['vehicle_type'] ?? ''));
    $message       = trim((string)($_POST['message'] ?? ''));
    $referral_code = trim((string)($_POST['referral_code'] ?? ''));
    $kvkk          = !empty($_POST['kvkk']);

    // Basic validation
    if ($name === '' || $email === '' || $phone === '') {
        flash_set('apply_error', 'error.required_fields');
        redirect('/apply');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash_set('apply_error', 'error.invalid_email');
        redirect('/apply');
    }

    if (!$kvkk) {
        flash_set('apply_error', 'error.kvkk_required');
        redirect('/apply');
    }

    try {
        $stmt = db()->prepare(
            'INSERT INTO applications (name, email, phone, city, vehicle_type, message, referral_code)
             VALUES (:name, :email, :phone, :city, :vtype, :msg, :ref)'
        );
        $stmt->execute([
            ':name'  => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':city'  => $city,
            ':vtype' => $vehicle_type,
            ':msg'   => $message,
            ':ref'   => $referral_code,
        ]);
    } catch (PDOException $e) {
        flash_set('apply_error', 'error.save_failed');
        redirect('/apply');
    }

    redirect('/apply/success');
}

// GET /apply/success
if ($path === '/apply/success' && $method === 'GET') {
    view('public/apply_success');
    exit;
}
