<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi – Rider</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.35);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
        }
        .login-logo {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: .3rem;
        }
        .login-title {
            text-align: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: .5rem;
        }
        .login-sub {
            text-align: center;
            font-size: .85rem;
            color: #888;
            margin-bottom: 1.8rem;
        }
        .alert {
            padding: .8rem 1rem;
            border-radius: 7px;
            margin-bottom: 1.2rem;
            font-size: .875rem;
            font-weight: 500;
        }
        .alert-danger  { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
        .alert-success { background: #e8f8f0; color: #27ae60; border: 1px solid #c3e6d0; }
        .alert-warning { background: #fef9e7; color: #b7950b; border: 1px solid #f9e4a0; }
        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: .82rem; font-weight: 600; margin-bottom: .35rem; color: #444; }
        input[type=email], input[type=password] {
            width: 100%;
            padding: .65rem .9rem;
            border: 1.5px solid #d0d7df;
            border-radius: 7px;
            font-size: .95rem;
            transition: border-color .2s;
        }
        input:focus { outline: none; border-color: #1a1a2e; }
        .btn-block {
            display: block;
            width: 100%;
            padding: .75rem;
            background: #1a1a2e;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: opacity .15s;
        }
        .btn-block:hover { opacity: .88; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-logo">🏍</div>
    <div class="login-title">Rider Admin</div>
    <div class="login-sub">Yönetici paneline giriş yapın</div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert"><?= e($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= e(BASE_PATH) ?>/admin/login" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email">E-posta</label>
            <input type="email" id="email" name="email"
                   value="<?= e($_POST['email'] ?? '') ?>"
                   required autofocus placeholder="admin@example.com">
        </div>

        <div class="form-group">
            <label for="password">Şifre</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-block">Giriş Yap</button>
    </form>
</div>

</body>
</html>
