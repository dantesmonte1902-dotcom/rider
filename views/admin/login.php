<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Giriş – Rider</title>
  <link rel="stylesheet" href="<?= e(BASE_PATH) ?>/assets/app.css">
</head>
<body>
<header class="topbar admin-topbar">
  <div class="container topbar-inner">
    <div class="brand">
      <div class="brand-badge">R</div>
      <div class="brand-text">Rider Admin</div>
    </div>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">
      <div class="card" style="max-width:420px;margin:0 auto">
        <div class="card-pad">
          <h1 class="h1" style="font-size:24px;margin-bottom:18px">Admin giriş</h1>

          <?php if (!empty($error)): ?>
            <div style="background:#ffe9e9;border:1px solid #ffb3b3;padding:10px 12px;border-radius:12px;margin:0 0 16px;">
              <?= e($error) ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="<?= e(BASE_PATH) ?>/admin/login" autocomplete="off">
            <?= csrf_field() ?>

            <div class="field">
              <label class="label">E-posta</label>
              <input class="input" type="email" name="email"
                     value="<?= e($_POST['email'] ?? '') ?>"
                     placeholder="admin@example.com" autofocus>
            </div>

            <div class="field">
              <label class="label">Şifre</label>
              <input class="input" type="password" name="password" placeholder="••••••••">
            </div>

            <div style="height:8px"></div>
            <button class="btn" type="submit"
                    style="background:var(--green);color:#fff">Giriş yap</button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="container footer-inner">
    <div class="muted">&copy; <?= date('Y') ?> Rider</div>
  </div>
</footer>
</body>
</html>
