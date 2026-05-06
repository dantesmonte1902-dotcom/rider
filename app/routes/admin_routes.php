<?php
declare(strict_types=1);

// ── Admin auth routes ─────────────────────────────────────────────────────────

// GET /admin/login
if ($path === '/admin/login' && $method === 'GET') {
    view('admin/login', [
        'error'    => flash_get('admin_login_failed'),
        'success'  => flash_get('admin_login_success'),
    ]);
    exit;
}

// POST /admin/login
if ($path === '/admin/login' && $method === 'POST') {
    csrf_validate();

    $email = trim((string)($_POST['email'] ?? ''));
    $pass  = (string)($_POST['password'] ?? '');

    if ($email === '' || $pass === '') {
        flash_set('admin_login_failed', 'E-posta ve şifre boş bırakılamaz.');
        redirect('/admin/login');
    }

    try {
        $stmt = db()->prepare('SELECT id, email, password_hash FROM admin_users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        flash_set('admin_login_failed', 'Veritabanı hatası: ' . $e->getMessage());
        redirect('/admin/login');
    }

    if (!$user || !password_verify($pass, $user['password_hash'])) {
        // Intentionally vague message to prevent user enumeration
        flash_set('admin_login_failed', 'Geçersiz e-posta veya şifre.');
        redirect('/admin/login');
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

// GET /admin/applications
if ($path === '/admin/applications' && $method === 'GET') {
    require_admin();

    $status = $_GET['status'] ?? '';
    $search = trim($_GET['search'] ?? '');

    try {
        $where  = [];
        $params = [];

        if ($status !== '') {
            $where[]           = 'status = :status';
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $where[]           = '(name LIKE :search OR email LIKE :search OR city LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql = 'SELECT * FROM applications';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $applications = $stmt->fetchAll();
    } catch (PDOException $e) {
        $applications = [];
        $db_error     = $e->getMessage();
    }

    view('admin/applications', [
        'applications' => $applications,
        'filter_status' => $status,
        'filter_search' => $search,
        'db_error'      => $db_error ?? '',
        'success'       => flash_get('app_success'),
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
        flash_set('app_success', 'Geçersiz durum.');
        redirect('/admin/applications');
    }

    try {
        $stmt = db()->prepare('UPDATE applications SET status = :s WHERE id = :id');
        $stmt->execute([':s' => $new_status, ':id' => $id]);
        flash_set('app_success', 'Başvuru durumu güncellendi.');
    } catch (PDOException $e) {
        flash_set('app_success', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/applications');
}

// GET /admin  → redirect to applications
if (($path === '/admin' || $path === '') && $method === 'GET') {
    redirect('/admin/applications');
}
