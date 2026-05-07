<?php
declare(strict_types=1);

if (!function_exists('_admin_normalize_application_filters')) {
    /**
     * @return array<string, string>
     */
    function _admin_normalize_application_filters(array $src): array
    {
        $status       = trim((string)($src['status'] ?? ''));
        $search       = trim((string)($src['search'] ?? ''));
        $vehicle      = trim((string)($src['vehicle'] ?? ''));
        $period       = trim((string)($src['period'] ?? ''));
        $date_from    = trim((string)($src['date_from'] ?? ''));
        $date_to      = trim((string)($src['date_to'] ?? ''));
        $sort         = strtolower(trim((string)($src['sort'] ?? 'desc')));

        if (!in_array($status, ['', 'pending', 'approved', 'rejected'], true)) {
            $status = '';
        }
        if (!in_array($period, ['', 'today', 'week', 'month', 'custom'], true)) {
            $period = '';
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from)) {
            $date_from = '';
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
            $date_to = '';
        }
        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        return [
            'status'    => $status,
            'search'    => $search,
            'vehicle'   => $vehicle,
            'period'    => $period,
            'date_from' => $date_from,
            'date_to'   => $date_to,
            'sort'      => $sort,
        ];
    }
}

if (!function_exists('_admin_build_application_where')) {
    /**
     * @param array<string, string> $filters
     * @return array{where:string, params:array<string, mixed>}
     */
    function _admin_build_application_where(array $filters): array
    {
        $where  = [];
        $params = [];

        if ($filters['status'] !== '') {
            $where[]           = 'status = :status';
            $params[':status'] = $filters['status'];
        }

        if ($filters['search'] !== '') {
            $where[]            = '(name LIKE :search OR email LIKE :search OR city LIKE :search OR phone LIKE :search)';
            $params[':search']  = '%' . $filters['search'] . '%';
        }

        if ($filters['vehicle'] !== '') {
            $where[]             = 'vehicle_type LIKE :vehicle';
            $params[':vehicle']  = '%' . $filters['vehicle'] . '%';
        }

        if ($filters['period'] === 'today') {
            $where[] = 'DATE(created_at) = CURDATE()';
        } elseif ($filters['period'] === 'week') {
            $where[] = 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
        } elseif ($filters['period'] === 'month') {
            $where[] = 'created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
        } elseif ($filters['period'] === 'custom') {
            if ($filters['date_from'] !== '') {
                $where[]              = 'created_at >= :date_from';
                $params[':date_from'] = $filters['date_from'] . ' 00:00:00';
            }
            if ($filters['date_to'] !== '') {
                $where[]            = 'created_at <= :date_to';
                $params[':date_to'] = $filters['date_to'] . ' 23:59:59';
            }
        }

        return [
            'where'  => $where ? (' WHERE ' . implode(' AND ', $where)) : '',
            'params' => $params,
        ];
    }
}

if (!function_exists('_admin_try_log_status_change')) {
    function _admin_try_log_status_change(int $application_id, string $old_status, string $new_status): void
    {
        try {
            $stmt = db()->prepare(
                'INSERT INTO application_status_logs (application_id, old_status, new_status, changed_by) VALUES (:app, :old, :new, :by)'
            );
            $stmt->execute([
                ':app' => $application_id,
                ':old' => $old_status,
                ':new' => $new_status,
                ':by'  => (int)($_SESSION['admin_id'] ?? 0),
            ]);
        } catch (PDOException $e) {
            // Backward compatibility: ignore when table does not exist
        }
    }
}

if (!function_exists('_admin_status_mail_text')) {
    /** @return array{subject:string, body:string} */
    function _admin_status_mail_text(string $name, string $status): array
    {
        $statusLabel = [
            'pending'  => 'Beklemede',
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi',
        ][$status] ?? $status;

        return [
            'subject' => 'Rider başvuru durumunuz güncellendi',
            'body'    => "Merhaba {$name},\n\nBaşvuru durumunuz: {$statusLabel}\n\nRider ekibi.",
        ];
    }
}

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
        view('admin/login', ['error' => 'Geçersiz e-posta veya şifre.']);
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['admin_id']    = $user['id'];
    $_SESSION['admin_email'] = $user['email'];

    redirect('/admin');
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

    $filters = _admin_normalize_application_filters($_GET);
    $built   = _admin_build_application_where($filters);

    try {
        $sql = 'SELECT id, name, email, phone, city, vehicle_type, referral_code, message, admin_note, status, created_at
                FROM applications' . $built['where'] . ' ORDER BY created_at ' . strtoupper($filters['sort']);
        $stmt = db()->prepare($sql);
        $stmt->execute($built['params']);
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        $rows = [];
    }

    $filename = 'rider-basvurular-' . date('Y-m-d') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF");
    fputcsv($out, ['#', 'Ad Soyad', 'E-posta', 'Telefon', 'Şehir', 'Araç Tipi', 'Davet Kodu', 'Mesaj', 'Admin Notu', 'Durum', 'Tarih']);
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
            $row['message'] ?? '',
            $row['admin_note'] ?? '',
            $statusLabels[$row['status']] ?? $row['status'],
            date('d.m.Y H:i', strtotime($row['created_at'])),
        ]);
    }
    fclose($out);
    exit;
}

// GET /admin/applications/{id} — detail
if (preg_match('#^/admin/applications/(\d+)$#', $path, $m) && $method === 'GET') {
    require_admin();

    $id = (int)$m[1];

    try {
        $stmt = db()->prepare('SELECT * FROM applications WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $application = $stmt->fetch();

        if (!$application) {
            flash_set('app_error', 'Başvuru bulunamadı.');
            redirect('/admin/applications');
        }

        try {
            $hStmt = db()->prepare('SELECT l.*, u.email AS admin_email
                                    FROM application_status_logs l
                                    LEFT JOIN admin_users u ON u.id = l.changed_by
                                    WHERE l.application_id = :id
                                    ORDER BY l.changed_at DESC');
            $hStmt->execute([':id' => $id]);
            $history = $hStmt->fetchAll();
        } catch (PDOException $e) {
            $history = [];
        }
    } catch (PDOException $e) {
        flash_set('app_error', 'Hata: ' . $e->getMessage());
        redirect('/admin/applications');
    }

    view('admin/application_detail', [
        'application' => $application,
        'history'     => $history,
        'success'     => flash_get('app_success'),
        'error'       => flash_get('app_error'),
    ]);
    exit;
}

// POST /admin/applications/{id}/note
if (preg_match('#^/admin/applications/(\d+)/note$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id   = (int)$m[1];
    $note = trim((string)($_POST['admin_note'] ?? ''));

    try {
        $stmt = db()->prepare('UPDATE applications SET admin_note = :note WHERE id = :id');
        $stmt->execute([':note' => $note, ':id' => $id]);
        flash_set('app_success', 'Admin notu güncellendi.');
    } catch (PDOException $e) {
        flash_set('app_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/applications/' . $id);
}

// POST /admin/applications/{id}/delete
if (preg_match('#^/admin/applications/(\d+)/delete$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id = (int)$m[1];

    try {
        try {
            $stmt = db()->prepare('DELETE FROM application_status_logs WHERE application_id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // ignore for legacy schema
        }

        $stmt = db()->prepare('DELETE FROM applications WHERE id = :id');
        $stmt->execute([':id' => $id]);
        flash_set('app_success', 'Başvuru silindi.');
    } catch (PDOException $e) {
        flash_set('app_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/applications');
}

// POST /admin/applications/bulk
if ($path === '/admin/applications/bulk' && $method === 'POST') {
    require_admin();
    csrf_validate();

    $action = (string)($_POST['bulk_action'] ?? '');
    $idsRaw = $_POST['ids'] ?? [];

    $ids = [];
    if (is_array($idsRaw)) {
        foreach ($idsRaw as $idRaw) {
            $id = (int)$idRaw;
            if ($id > 0) {
                $ids[] = $id;
            }
        }
    }
    $ids = array_values(array_unique($ids));

    if ($ids === []) {
        flash_set('app_error', 'Toplu işlem için en az bir başvuru seçin.');
        redirect('/admin/applications');
    }

    $allowedStatusActions = ['pending', 'approved', 'rejected'];

    try {
        if ($action === 'delete') {
            $in = implode(',', array_fill(0, count($ids), '?'));

            try {
                $stmtLog = db()->prepare('DELETE FROM application_status_logs WHERE application_id IN (' . $in . ')');
                $stmtLog->execute($ids);
            } catch (PDOException $e) {
                // ignore
            }

            $stmt = db()->prepare('DELETE FROM applications WHERE id IN (' . $in . ')');
            $stmt->execute($ids);
            flash_set('app_success', 'Seçili başvurular silindi.');
        } elseif (in_array($action, $allowedStatusActions, true)) {
            $sel = db()->prepare('SELECT id, status, email, name FROM applications WHERE id = :id LIMIT 1');
            $upd = db()->prepare('UPDATE applications SET status = :s WHERE id = :id');

            foreach ($ids as $id) {
                $sel->execute([':id' => $id]);
                $row = $sel->fetch();
                if (!$row) {
                    continue;
                }
                $old = (string)$row['status'];
                if ($old === $action) {
                    continue;
                }

                $upd->execute([':s' => $action, ':id' => $id]);
                _admin_try_log_status_change($id, $old, $action);

                if (!empty($row['email'])) {
                    $mail = _admin_status_mail_text((string)($row['name'] ?? ''), $action);
                    @mail((string)$row['email'], $mail['subject'], $mail['body'], "Content-Type: text/plain; charset=UTF-8\r\n");
                }
            }

            flash_set('app_success', 'Toplu durum güncellemesi tamamlandı.');
        } else {
            flash_set('app_error', 'Geçersiz toplu işlem.');
        }
    } catch (PDOException $e) {
        flash_set('app_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/applications');
}

// POST /admin/applications/{id}/status  — update application status
if (preg_match('#^/admin/applications/(\d+)/status$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id         = (int)$m[1];
    $new_status = (string)($_POST['status'] ?? '');

    $allowed = ['pending', 'approved', 'rejected'];
    if (!in_array($new_status, $allowed, true)) {
        flash_set('app_error', 'Geçersiz durum.');
        redirect('/admin/applications');
    }

    $redirectTo = '/admin/applications';
    $back       = (string)($_POST['back_to'] ?? '');
    if ($back === 'detail') {
        $redirectTo = '/admin/applications/' . $id;
    }

    try {
        $sel = db()->prepare('SELECT status, email, name FROM applications WHERE id = :id LIMIT 1');
        $sel->execute([':id' => $id]);
        $row = $sel->fetch();

        if (!$row) {
            flash_set('app_error', 'Başvuru bulunamadı.');
            redirect($redirectTo);
        }

        $old_status = (string)$row['status'];

        if ($old_status !== $new_status) {
            $stmt = db()->prepare('UPDATE applications SET status = :s WHERE id = :id');
            $stmt->execute([':s' => $new_status, ':id' => $id]);

            _admin_try_log_status_change($id, $old_status, $new_status);

            if (!empty($row['email'])) {
                $mail = _admin_status_mail_text((string)($row['name'] ?? ''), $new_status);
                @mail((string)$row['email'], $mail['subject'], $mail['body'], "Content-Type: text/plain; charset=UTF-8\r\n");
            }
        }

        flash_set('app_success', 'Başvuru durumu güncellendi.');
    } catch (PDOException $e) {
        flash_set('app_error', 'Hata: ' . $e->getMessage());
    }

    redirect($redirectTo);
}

// GET /admin/applications
if ($path === '/admin/applications' && $method === 'GET') {
    require_admin();

    $filters = _admin_normalize_application_filters($_GET);
    $built   = _admin_build_application_where($filters);

    $page       = max(1, (int)($_GET['page'] ?? 1));
    $perPage    = 20;
    $offset     = ($page - 1) * $perPage;

    try {
        $countStmt = db()->prepare('SELECT COUNT(*) FROM applications' . $built['where']);
        $countStmt->execute($built['params']);
        $totalRows = (int)$countStmt->fetchColumn();

        $sql = 'SELECT * FROM applications' . $built['where'] . ' ORDER BY created_at ' . strtoupper($filters['sort']) . ' LIMIT :limit OFFSET :offset';
        $stmt = db()->prepare($sql);
        foreach ($built['params'] as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $applications = $stmt->fetchAll();

        $vehicle_types = db()->query('SELECT DISTINCT vehicle_type FROM applications WHERE vehicle_type != "" ORDER BY vehicle_type ASC')->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $applications  = [];
        $vehicle_types = [];
        $db_error      = $e->getMessage();
        $totalRows     = 0;
    }

    $totalPages = max(1, (int)ceil($totalRows / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }

    view('admin/applications', [
        'applications'   => $applications,
        'filter_status'  => $filters['status'],
        'filter_search'  => $filters['search'],
        'filter_vehicle' => $filters['vehicle'],
        'filter_period'  => $filters['period'],
        'filter_from'    => $filters['date_from'],
        'filter_to'      => $filters['date_to'],
        'sort'           => $filters['sort'],
        'vehicle_types'  => $vehicle_types ?? [],
        'db_error'       => $db_error ?? '',
        'success'        => flash_get('app_success'),
        'error'          => flash_get('app_error'),
        'current_page'   => $page,
        'total_pages'    => $totalPages,
        'per_page'       => $perPage,
        'total_rows'     => $totalRows,
    ]);
    exit;
}

// GET /admin/settings/password
if ($path === '/admin/settings/password' && $method === 'GET') {
    require_admin();

    view('admin/settings_password', [
        'success' => flash_get('password_success'),
        'error'   => flash_get('password_error'),
    ]);
    exit;
}

// POST /admin/settings/password
if ($path === '/admin/settings/password' && $method === 'POST') {
    require_admin();
    csrf_validate();

    $current = (string)($_POST['current_password'] ?? '');
    $new     = (string)($_POST['new_password'] ?? '');
    $confirm = (string)($_POST['new_password_confirm'] ?? '');

    if ($current === '' || $new === '' || $confirm === '') {
        flash_set('password_error', 'Tüm alanları doldurun.');
        redirect('/admin/settings/password');
    }
    if (strlen($new) < 8) {
        flash_set('password_error', 'Yeni şifre en az 8 karakter olmalı.');
        redirect('/admin/settings/password');
    }
    if (!hash_equals($new, $confirm)) {
        flash_set('password_error', 'Yeni şifre ve tekrar alanı eşleşmiyor.');
        redirect('/admin/settings/password');
    }

    try {
        $stmt = db()->prepare('SELECT password_hash FROM admin_users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => (int)$_SESSION['admin_id']]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($current, (string)$row['password_hash'])) {
            flash_set('password_error', 'Mevcut şifre hatalı.');
            redirect('/admin/settings/password');
        }

        $upd = db()->prepare('UPDATE admin_users SET password_hash = :h WHERE id = :id');
        $upd->execute([
            ':h'  => password_hash($new, PASSWORD_DEFAULT),
            ':id' => (int)$_SESSION['admin_id'],
        ]);

        flash_set('password_success', 'Şifreniz güncellendi.');
    } catch (PDOException $e) {
        flash_set('password_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/settings/password');
}

// GET /admin/users
if ($path === '/admin/users' && $method === 'GET') {
    require_admin();

    try {
        $admins = db()->query('SELECT id, email, created_at FROM admin_users ORDER BY id ASC')->fetchAll();
    } catch (PDOException $e) {
        $admins   = [];
        $db_error = $e->getMessage();
    }

    view('admin/users', [
        'admins'   => $admins,
        'db_error' => $db_error ?? '',
        'success'  => flash_get('admin_user_success'),
        'error'    => flash_get('admin_user_error'),
    ]);
    exit;
}

// POST /admin/users
if ($path === '/admin/users' && $method === 'POST') {
    require_admin();
    csrf_validate();

    $email    = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash_set('admin_user_error', 'Geçerli bir e-posta girin.');
        redirect('/admin/users');
    }
    if (strlen($password) < 8) {
        flash_set('admin_user_error', 'Şifre en az 8 karakter olmalı.');
        redirect('/admin/users');
    }

    try {
        $stmt = db()->prepare('INSERT INTO admin_users (email, password_hash) VALUES (:email, :hash)');
        $stmt->execute([
            ':email' => $email,
            ':hash'  => password_hash($password, PASSWORD_DEFAULT),
        ]);
        flash_set('admin_user_success', 'Yeni admin eklendi.');
    } catch (PDOException $e) {
        flash_set('admin_user_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/users');
}

// POST /admin/users/{id}/delete
if (preg_match('#^/admin/users/(\d+)/delete$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id = (int)$m[1];

    if ($id === (int)($_SESSION['admin_id'] ?? 0)) {
        flash_set('admin_user_error', 'Giriş yaptığınız hesabı silemezsiniz.');
        redirect('/admin/users');
    }

    try {
        $stmt = db()->prepare('DELETE FROM admin_users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        flash_set('admin_user_success', 'Admin silindi.');
    } catch (PDOException $e) {
        flash_set('admin_user_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/users');
}

// GET /admin/cities
if ($path === '/admin/cities' && $method === 'GET') {
    require_admin();
    try {
        $cities = db()->query('SELECT id, name, name_en, name_tr, name_ar FROM cities ORDER BY name ASC')->fetchAll();
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
    $name    = trim((string)($_POST['name'] ?? ''));
    $name_en = trim((string)($_POST['name_en'] ?? ''));
    $name_tr = trim((string)($_POST['name_tr'] ?? ''));
    $name_ar = trim((string)($_POST['name_ar'] ?? ''));

    if ($name === '') {
        flash_set('city_error', 'Boşnakça şehir adı boş bırakılamaz.');
        redirect('/admin/cities');
    }

    try {
        $stmt = db()->prepare('INSERT INTO cities (name, name_en, name_tr, name_ar) VALUES (:name, :en, :tr, :ar)');
        $stmt->execute([
            ':name' => $name,
            ':en'   => $name_en !== '' ? $name_en : null,
            ':tr'   => $name_tr !== '' ? $name_tr : null,
            ':ar'   => $name_ar !== '' ? $name_ar : null,
        ]);
        flash_set('city_success', '"' . $name . '" eklendi.');
    } catch (PDOException $e) {
        flash_set('city_error', 'Hata: ' . $e->getMessage());
    }
    redirect('/admin/cities');
}

// POST /admin/cities/{id}/edit
if (preg_match('#^/admin/cities/(\d+)/edit$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id      = (int)$m[1];
    $name    = trim((string)($_POST['name'] ?? ''));
    $name_en = trim((string)($_POST['name_en'] ?? ''));
    $name_tr = trim((string)($_POST['name_tr'] ?? ''));
    $name_ar = trim((string)($_POST['name_ar'] ?? ''));

    if ($name === '') {
        flash_set('city_error', 'Boşnakça şehir adı boş bırakılamaz.');
        redirect('/admin/cities');
    }

    try {
        $stmt = db()->prepare('UPDATE cities SET name = :name, name_en = :en, name_tr = :tr, name_ar = :ar WHERE id = :id');
        $stmt->execute([
            ':name' => $name,
            ':en'   => $name_en !== '' ? $name_en : null,
            ':tr'   => $name_tr !== '' ? $name_tr : null,
            ':ar'   => $name_ar !== '' ? $name_ar : null,
            ':id'   => $id,
        ]);
        flash_set('city_success', 'Şehir güncellendi.');
    } catch (PDOException $e) {
        flash_set('city_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/cities');
}

// POST /admin/cities/{id}/delete
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

// GET /admin/vehicles
if ($path === '/admin/vehicles' && $method === 'GET') {
    require_admin();
    try {
        $vehicle_types = db()->query('SELECT id, name, name_en, name_tr, name_ar FROM vehicle_types ORDER BY name ASC')->fetchAll();
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
    $name    = trim((string)($_POST['name'] ?? ''));
    $name_en = trim((string)($_POST['name_en'] ?? ''));
    $name_tr = trim((string)($_POST['name_tr'] ?? ''));
    $name_ar = trim((string)($_POST['name_ar'] ?? ''));

    if ($name === '') {
        flash_set('vehicle_error', 'Boşnakça araç tipi adı boş bırakılamaz.');
        redirect('/admin/vehicles');
    }

    try {
        $stmt = db()->prepare('INSERT INTO vehicle_types (name, name_en, name_tr, name_ar) VALUES (:name, :en, :tr, :ar)');
        $stmt->execute([
            ':name' => $name,
            ':en'   => $name_en !== '' ? $name_en : null,
            ':tr'   => $name_tr !== '' ? $name_tr : null,
            ':ar'   => $name_ar !== '' ? $name_ar : null,
        ]);
        flash_set('vehicle_success', '"' . $name . '" eklendi.');
    } catch (PDOException $e) {
        flash_set('vehicle_error', 'Hata: ' . $e->getMessage());
    }
    redirect('/admin/vehicles');
}

// POST /admin/vehicles/{id}/edit
if (preg_match('#^/admin/vehicles/(\d+)/edit$#', $path, $m) && $method === 'POST') {
    require_admin();
    csrf_validate();

    $id      = (int)$m[1];
    $name    = trim((string)($_POST['name'] ?? ''));
    $name_en = trim((string)($_POST['name_en'] ?? ''));
    $name_tr = trim((string)($_POST['name_tr'] ?? ''));
    $name_ar = trim((string)($_POST['name_ar'] ?? ''));

    if ($name === '') {
        flash_set('vehicle_error', 'Boşnakça araç tipi adı boş bırakılamaz.');
        redirect('/admin/vehicles');
    }

    try {
        $stmt = db()->prepare('UPDATE vehicle_types SET name = :name, name_en = :en, name_tr = :tr, name_ar = :ar WHERE id = :id');
        $stmt->execute([
            ':name' => $name,
            ':en'   => $name_en !== '' ? $name_en : null,
            ':tr'   => $name_tr !== '' ? $name_tr : null,
            ':ar'   => $name_ar !== '' ? $name_ar : null,
            ':id'   => $id,
        ]);
        flash_set('vehicle_success', 'Araç tipi güncellendi.');
    } catch (PDOException $e) {
        flash_set('vehicle_error', 'Hata: ' . $e->getMessage());
    }

    redirect('/admin/vehicles');
}

// POST /admin/vehicles/{id}/delete
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

// GET /admin — dashboard
if (($path === '/admin' || $path === '') && $method === 'GET') {
    require_admin();

    try {
        $summary = db()->query(
            "SELECT
                COUNT(*) AS total,
                SUM(status='pending') AS pending_count,
                SUM(status='approved') AS approved_count,
                SUM(status='rejected') AS rejected_count,
                SUM(created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) AS week_count
             FROM applications"
        )->fetch();

        $topCityStmt = db()->query(
            "SELECT city, COUNT(*) AS cnt
             FROM applications
             WHERE city != ''
             GROUP BY city
             ORDER BY cnt DESC, city ASC
             LIMIT 1"
        );
        $topCity = $topCityStmt->fetch();

        $topVehicleStmt = db()->query(
            "SELECT vehicle_type, COUNT(*) AS cnt
             FROM applications
             WHERE vehicle_type != ''
             GROUP BY vehicle_type
             ORDER BY cnt DESC, vehicle_type ASC
             LIMIT 1"
        );
        $topVehicle = $topVehicleStmt->fetch();

        $recent = db()->query('SELECT id, name, city, vehicle_type, status, created_at FROM applications ORDER BY created_at DESC LIMIT 8')->fetchAll();
    } catch (PDOException $e) {
        $summary = [
            'total'          => 0,
            'pending_count'  => 0,
            'approved_count' => 0,
            'rejected_count' => 0,
            'week_count'     => 0,
        ];
        $topCity    = null;
        $topVehicle = null;
        $recent     = [];
        $db_error   = $e->getMessage();
    }

    view('admin/dashboard', [
        'summary'    => $summary,
        'top_city'   => $topCity,
        'top_vehicle'=> $topVehicle,
        'recent'     => $recent,
        'db_error'   => $db_error ?? '',
    ]);
    exit;
}
