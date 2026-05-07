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

/**
 * Fetch cities and vehicle_types with localised labels.
 * Falls back to the canonical name column for legacy schemas.
 *
 * @return array{cities: list<array<string,mixed>>, vehicle_types: list<array<string,mixed>>}
 */
function _apply_fetch_options(): array
{
    $lang        = $_SESSION['lang'] ?? 'bs';
    $valid_langs = ['bs', 'en', 'tr', 'ar'];
    if (!in_array($lang, $valid_langs, true)) {
        $lang = 'bs';
    }
    $lang_col = $lang === 'bs'
        ? 'name AS label'
        : "COALESCE(name_{$lang}, name) AS label";

    try {
        $cities        = db()->query("SELECT id, name, {$lang_col} FROM cities ORDER BY label ASC")->fetchAll();
        $vehicle_types = db()->query("SELECT id, name, {$lang_col} FROM vehicle_types ORDER BY label ASC")->fetchAll();
    } catch (PDOException $e) {
        // Fallback for legacy schemas where translated columns do not exist yet
        try {
            $cities        = db()->query('SELECT id, name, name AS label FROM cities ORDER BY name ASC')->fetchAll();
            $vehicle_types = db()->query('SELECT id, name, name AS label FROM vehicle_types ORDER BY name ASC')->fetchAll();
        } catch (PDOException $e) {
            $cities        = [];
            $vehicle_types = [];
        }
    }
    return ['cities' => $cities, 'vehicle_types' => $vehicle_types];
}

// GET /apply
if ($path === '/apply' && $method === 'GET') {
    $opts = _apply_fetch_options();
    view('public/apply', [
        'cities'        => $opts['cities'],
        'vehicle_types' => $opts['vehicle_types'],
        'error'         => flash_get('apply_error'),
        'error_detail'  => '',
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

    // Collect the first validation error (if any)
    $error = '';
    $error_detail = '';

    if ($name === '' || $email === '' || $phone === '') {
        $error = 'error.required_fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'error.invalid_email';
    } elseif (!$kvkk) {
        $error = 'error.kvkk_required';
    }

    // Attempt DB insert only when validation passed
    if ($error === '') {
        try {
            // Build insert dynamically so older/newer schemas continue to accept submissions
            $existing_columns = [];
            foreach (db()->query('SHOW COLUMNS FROM applications')->fetchAll() as $row) {
                if (!empty($row['Field'])) {
                    $existing_columns[] = (string) $row['Field'];
                }
            }

            $value_map = [
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'city'          => $city,
                'vehicle_type'  => $vehicle_type,
                'message'       => $message,
                'referral_code' => $referral_code,
            ];

            // Required minimum for this endpoint
            if (!in_array('name', $existing_columns, true) ||
                !in_array('email', $existing_columns, true) ||
                !in_array('phone', $existing_columns, true)) {
                throw new PDOException('applications table missing required columns');
            }

            $insert_columns = [];
            $params = [];
            foreach ($value_map as $col => $val) {
                if (in_array($col, $existing_columns, true)) {
                    $insert_columns[] = $col;
                    $params[':' . $col] = $val;
                }
            }

            $placeholders = array_map(static fn(string $col): string => ':' . $col, $insert_columns);
            $sql = 'INSERT INTO applications (' . implode(', ', $insert_columns) . ')
                    VALUES (' . implode(', ', $placeholders) . ')';
            $stmt = db()->prepare($sql);
            $stmt->execute($params);

            // All good — redirect to success page (PRG pattern)
            redirect('/apply/success');
        } catch (PDOException $e) {
            error_log('[apply] save failed' . (IS_LOCAL ? ': ' . $e->getMessage() : ''));
            $error = 'error.save_failed';
            if (IS_LOCAL) {
                $error_detail = $e->getMessage();
            }
        }
    }

    // Re-render the form in-place so that:
    //   • $_POST data is still available for field pre-fill
    //   • The inline script in apply.php restores the user to step 2
    //   • No round-trip redirect is needed
    $opts = _apply_fetch_options();
    view('public/apply', [
        'cities'        => $opts['cities'],
        'vehicle_types' => $opts['vehicle_types'],
        'error'         => $error,
        'error_detail'  => $error_detail,
    ]);
    exit;
}

// GET /apply/success
if ($path === '/apply/success' && $method === 'GET') {
    view('public/apply_success');
    exit;
}
