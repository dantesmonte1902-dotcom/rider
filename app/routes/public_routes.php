<?php
declare(strict_types=1);

// ── Public routes ─────────────────────────────────────────────────────────────

// GET / — home/landing page
if ($path === '/' && $method === 'GET') {
    view('public/home');
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

    $name         = trim((string)($_POST['name'] ?? ''));
    $email        = trim((string)($_POST['email'] ?? ''));
    $phone        = trim((string)($_POST['phone'] ?? ''));
    $city         = trim((string)($_POST['city'] ?? ''));
    $vehicle_type = trim((string)($_POST['vehicle_type'] ?? ''));
    $message      = trim((string)($_POST['message'] ?? ''));

    // Basic validation
    if ($name === '' || $email === '' || $phone === '') {
        flash_set('apply_error', 'Ad, e-posta ve telefon alanları zorunludur.');
        redirect('/apply');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash_set('apply_error', 'Geçerli bir e-posta adresi girin.');
        redirect('/apply');
    }

    try {
        $stmt = db()->prepare(
            'INSERT INTO applications (name, email, phone, city, vehicle_type, message)
             VALUES (:name, :email, :phone, :city, :vtype, :msg)'
        );
        $stmt->execute([
            ':name'  => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':city'  => $city,
            ':vtype' => $vehicle_type,
            ':msg'   => $message,
        ]);
    } catch (PDOException $e) {
        flash_set('apply_error', 'Başvurunuz kaydedilemedi. Lütfen tekrar deneyin.');
        redirect('/apply');
    }

    redirect('/apply/success');
}

// GET /apply/success
if ($path === '/apply/success' && $method === 'GET') {
    view('public/apply_success');
    exit;
}
