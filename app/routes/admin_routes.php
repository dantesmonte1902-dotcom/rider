<?php
declare(strict_types=1);

// ── Admin auth routes ─────────────────────────────────────────────────────────

// GET /admin/login
if ($path === '/admin/login' && $method === 'GET') {
    view('admin/login', [
        'error' => flash_get('admin_login_failed'),
    ]);
    exit;
}

// POST /admin/login
if ($path === '/admin/login' && $method === 'POST') {
    csrf_validate();

    $email = trim((string)($_POST['email'] ?? ''));
    $pass  = (string)($_POST['password'] ?? '');

    if ($email === '' || $pass === '') {
        view('admin/login', ['error' => 'E-posta ve şifre boş bırakılamaz.']);
        exit;
    }

    try {
        $stmt = db()->prepare('SELECT id, email, password_hash FROM admin_users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        view('admin/login', ['error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        exit;
    }

    if (!$user || !password_verify($pass, $user['password_hash'])) {
        // Intentionally vague message to prevent user enumeration
        view('admin/login', ['error' => 'Geçersiz e-posta veya şifre.']);
        exit;
    }

    // Regenerate session ID to prevent fixation attacks
    session_regenerate_id(true);

    $_SESSION['admin_id']    = $user['id'];
    $_SESSION['admin_email'] = $user['email'];

    redirect('/admin/applications');
}

// GET /admin/logout
if ($path === '/admin/logout' && $method === 'GET') {
    $_SESSION = [];
    session_destroy();
    redirect('/admin/login');
}

// GET /admin/applications/export — CSV export
if ($path === '/admin/applications/export' && $method === 'GET') {
    require_admin();

    $status         = $_GET['status'] ?? '';
    $search         = trim($_GET['search'] ?? '');
    $vehicle_filter = trim($_GET['vehicle'] ?? '');

    try {
        $where  = [];
        $params = [];

        if ($status !== '') {
            $where[]           = 'status = :status';
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $where[]            = '(name LIKE :search1 OR email LIKE :search2 OR city LIKE :search3)';
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if ($vehicle_filter !== '') {
            $where[]            = 'vehicle_type LIKE :vehicle';
            $params[':vehicle'] = '%' . $vehicle_filter . '%';
        }

        $sql = 'SELECT id, name, email, phone, city, vehicle_type, referral_code, message, status, created_at FROM applications';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        $rows = [];
    }

    $filename = 'rider-basvurular-' . date('Y-m-d') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    // UTF-8 BOM for Excel compatibility
    fputs($out, "\xEF\xBB\xBF");
    fputcsv($out, ['#', 'Ad Soyad', 'E-posta', 'Telefon', 'Şehir', 'Araç Tipi', 'Davet Kodu', 'Not', 'Durum', 'Tarih']);
    $statusLabels = ['pending' => 'Bekliyor', 'approved' => 'Onaylandı', 'rejected' => 'Reddedildi'];
    foreach ($rows as $row) {
        fputcsv($out, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['city'],
            $row['vehicle_type'],
            $row['referral_code'] ?? '',
            $row['message'],
            $statusLabels[$row['status']] ?? $row['status'],
            date('d.m.Y H:i', strtotime($row['created_at'])),
        ]);
    }
    fclose($out);
    exit;
}

// GET /admin/applications
if ($path === '/admin/applications' && $method === 'GET') {
    require_admin();

    $status       = $_GET['status'] ?? '';
    $search       = trim($_GET['search'] ?? '');
    $vehicle_filter = trim($_GET['vehicle'] ?? '');

    try {
        $where  = [];
        $params = [];

        if ($status !== '') {
            $where[]           = 'status = :status';
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $where[]            = '(name LIKE :search1 OR email LIKE :search2 OR city LIKE :search3)';
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if ($vehicle_filter !== '') {
            $where[]             = 'vehicle_type LIKE :vehicle';
            $params[':vehicle']  = '%' . $vehicle_filter . '%';
        }

        $sql = 'SELECT * FROM applications';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $applications = $stmt->fetchAll();

        // Distinct vehicle types for filter dropdown
        $vehicle_types = db()->query('SELECT DISTINCT vehicle_type FROM applications WHERE vehicle_type != \'\' ORDER BY vehicle_type ASC')->fetchAll(\PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $applications  = [];
        $vehicle_types = [];
        $db_error      = $e->getMessage();
    }

    view('admin/applications', [
        'applications'   => $applications,
        'filter_status'  => $status,
        'filter_search'  => $search,
        'filter_vehicle' => $vehicle_filter,
        'vehicle_types'  => $vehicle_types ?? [],
        'db_error'       => $db_error ?? '',
        'success'        => flash_get('app_success'),
        'error'          => flash_get('app_error'),
    ]);
    exit;
}

// POST /admin/applications/{id}/status  — update application status
if (preg_match('#^/admin/applications/(\d+)/status$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id         = (int)$m[1];
    $new_status = $_POST['status'] ?? '';

    $allowed = ['pending', 'approved', 'rejected'];
    if (!in_array($new_status, $allowed, true)) {
        flash_set('app_error', 'Geçersiz durum.');
        redirect('/admin/applications');
    }

    try {
        $stmt = db()->prepare('UPDATE applications SET status = :s WHERE id = :id');
        $stmt->execute([':s' => $new_status, ':id' => $id]);
        flash_set('app_success', 'Başvuru durumu güncellendi.');
    } catch (PDOException $e) {
        flash_set('app_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/applications');
}

// GET /admin  → redirect to applications
if (($path === '/admin' || $path === '') && $method === 'GET') {
    redirect('/admin/applications');
}

// ── Cities ────────────────────────────────────────────────────────────────────

// GET /admin/cities
if ($path === '/admin/cities' && $method === 'GET') {
    require_admin();
    try {
        $cities = db()->query('SELECT id, name FROM cities ORDER BY name ASC')->fetchAll();
    } catch (PDOException $e) {
        $cities   = [];
        $db_error = $e->getMessage();
    }
    view('admin/cities', [
        'cities'   => $cities,
        'db_error' => $db_error ?? '',
        'success'  => flash_get('city_success'),
        'error'    => flash_get('city_error'),
    ]);
    exit;
}

// POST /admin/cities — add city
if ($path === '/admin/cities' && $method === 'POST') {
    require_admin();
    csrf_validate();
    $name = trim((string)($_POST['name'] ?? ''));
    if ($name === '') {
        flash_set('city_error', 'Şehir adı boş bırakılamaz.');
        redirect('/admin/cities');
    }
    try {
        $stmt = db()->prepare('INSERT INTO cities (name) VALUES (:name)');
        $stmt->execute([':name' => $name]);
        flash_set('city_success', '"' . $name . '" eklendi.');
    } catch (PDOException $e) {
        flash_set('city_error', 'Hata: ' . $e->getMessage());
    }
    redirect('/admin/cities');
}

// POST /admin/cities/{id}/delete — delete city
if (preg_match('#^/admin/cities/(\d+)/delete$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();
    $id = (int)$m[1];
    try {
        $stmt = db()->prepare('DELETE FROM cities WHERE id = :id');
        $stmt->execute([':id' => $id]);
        flash_set('city_success', 'Şehir silindi.');
    } catch (PDOException $e) {
        flash_set('city_error', 'Hata: ' . $e->getMessage());
    }
    redirect('/admin/cities');
}

// ── Vehicle types ─────────────────────────────────────────────────────────────

// GET /admin/vehicles
if ($path === '/admin/vehicles' && $method === 'GET') {
    require_admin();
    try {
        $vehicle_types = db()->query('SELECT id, name FROM vehicle_types ORDER BY name ASC')->fetchAll();
    } catch (PDOException $e) {
        $vehicle_types = [];
        $db_error      = $e->getMessage();
    }
    view('admin/vehicles', [
        'vehicle_types' => $vehicle_types,
        'db_error'      => $db_error ?? '',
        'success'       => flash_get('vehicle_success'),
        'error'         => flash_get('vehicle_error'),
    ]);
    exit;
}

// POST /admin/vehicles — add vehicle type
if ($path === '/admin/vehicles' && $method === 'POST') {
    require_admin();
    csrf_validate();
    $name = trim((string)($_POST['name'] ?? ''));
    if ($name === '') {
        flash_set('vehicle_error', 'Araç tipi adı boş bırakılamaz.');
        redirect('/admin/vehicles');
    }
    try {
        $stmt = db()->prepare('INSERT INTO vehicle_types (name) VALUES (:name)');
        $stmt->execute([':name' => $name]);
        flash_set('vehicle_success', '"' . $name . '" eklendi.');
    } catch (PDOException $e) {
        flash_set('vehicle_error', 'Hata: ' . $e->getMessage());
    }
    redirect('/admin/vehicles');
}

// POST /admin/vehicles/{id}/delete — delete vehicle type
if (preg_match('#^/admin/vehicles/(\d+)/delete$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();
    $id = (int)$m[1];
    try {
        $stmt = db()->prepare('DELETE FROM vehicle_types WHERE id = :id');
        $stmt->execute([':id' => $id]);
        flash_set('vehicle_success', 'Araç tipi silindi.');
    } catch (PDOException $e) {
        flash_set('vehicle_error', 'Hata: ' . $e->getMessage());
    }
    redirect('/admin/vehicles');
}
