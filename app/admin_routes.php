<?php
declare(strict_types=1);

function admin_require_auth(): void {
    if (!($_SESSION['admin_id'] ?? null)) {
        header('Location: ' . BASE_PATH . '/admin/login');
        exit;
    }
}

if ($path === '/admin/login' && $method === 'GET') {
    ensure_csrf_token();
    render('admin_login', ['error' => null]);
    exit;
}

if ($path === '/admin/login' && $method === 'POST') {
    csrf_verify();
    $email = trim((string)($_POST['email'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');

    $stmt = db()->prepare("SELECT id, password_hash FROM admin_users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$u || !password_verify($pass, (string)$u['password_hash'])) {
        ensure_csrf_token();
        render('admin_login', ['error' => t('admin_login_failed')]);
        exit;
    }

    $_SESSION['admin_id'] = (int)$u['id'];
    header('Location: ' . BASE_PATH . '/admin/applications');
    exit;
}

if ($path === '/admin/logout') {
    unset($_SESSION['admin_id']);
    header('Location: ' . BASE_PATH . '/admin/login');
    exit;
}

if ($path === '/admin/applications') {
    admin_require_auth();

    $status = $_GET['status'] ?? '';
    $q = trim((string)($_GET['q'] ?? ''));

    $sql = "SELECT * FROM applications WHERE 1=1";
    $params = [];

    if (is_string($status) && $status !== '') {
        $sql .= " AND status = :status";
        $params[':status'] = $status;
    }
    if ($q !== '') {
        $sql .= " AND (full_name LIKE :q OR phone LIKE :q)";
        $params[':q'] = '%' . $q . '%';
    }

    $sql .= " ORDER BY created_at DESC LIMIT 200";

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    render('admin_applications', ['apps' => $apps, 'status' => $status, 'q' => $q]);
    exit;
}