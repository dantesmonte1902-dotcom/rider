<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin Panel') ?> – Rider</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* ── Navbar ── */
        .navbar {
            background: #1a1a2e;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 56px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.35);
        }
        .navbar-brand { font-weight: 700; font-size: 1.2rem; letter-spacing: .05em; }
        .navbar-nav { display: flex; gap: 1.5rem; list-style: none; }
        .navbar-nav a {
            color: #c8d6e5;
            text-decoration: none;
            font-size: .9rem;
            transition: color .2s;
        }
        .navbar-nav a:hover, .navbar-nav a.active { color: #fff; }

        /* ── Main content ── */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        /* ── Alerts ── */
        .alert {
            padding: .85rem 1.2rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: .9rem;
        }
        .alert-danger  { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
        .alert-success { background: #e8f8f0; color: #27ae60; border: 1px solid #c3e6d0; }
        .alert-warning { background: #fef9e7; color: #b7950b; border: 1px solid #f9e4a0; }

        /* ── Buttons ── */
        .btn {
            display: inline-block;
            padding: .55rem 1.2rem;
            border: none;
            border-radius: 6px;
            font-size: .9rem;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s;
        }
        .btn:hover { opacity: .88; }
        .btn-primary   { background: #1a1a2e; color: #fff; }
        .btn-danger    { background: #e74c3c; color: #fff; }
        .btn-success   { background: #27ae60; color: #fff; }
        .btn-warning   { background: #f39c12; color: #fff; }
        .btn-secondary { background: #7f8c8d; color: #fff; }
        .btn-sm { padding: .35rem .75rem; font-size: .8rem; }

        /* ── Tables ── */
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        th, td { padding: .75rem 1rem; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background: #f8f9fa; font-weight: 600; color: #555; }
        tr:hover td { background: #f9fbfd; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .4rem; color: #444; }
        input[type=text], input[type=email], input[type=password],
        select, textarea {
            width: 100%;
            padding: .6rem .9rem;
            border: 1px solid #cdd5df;
            border-radius: 6px;
            font-size: .9rem;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #1a1a2e;
        }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: .2rem .55rem;
            border-radius: 99px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-pending  { background: #fef9e7; color: #b7950b; }
        .badge-approved { background: #e8f8f0; color: #27ae60; }
        .badge-rejected { background: #fde8e8; color: #c0392b; }

        /* ── Page heading ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.4rem; }
    </style>
</head>
<body>

<nav class="navbar">
    <span class="navbar-brand">🏍 Rider Admin</span>
    <ul class="navbar-nav">
        <li><a href="<?= e(BASE_PATH) ?>/admin/applications"
               class="<?= ($activePage ?? '') === 'applications' ? 'active' : '' ?>">Başvurular</a></li>
        <li><a href="<?= e(BASE_PATH) ?>/admin/logout">Çıkış Yap</a></li>
    </ul>
</nav>

<div class="container">
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <?= $content ?? '' ?>
</div>

</body>
</html>
