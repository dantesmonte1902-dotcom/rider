<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Admin Panel') ?> – Rider</title>
  <link rel="stylesheet" href="<?= e(BASE_PATH) ?>/assets/app.css">
</head>
<body>

<header class="topbar admin-topbar">
  <div class="container topbar-inner">
    <div class="brand">
      <div class="brand-badge">R</div>
      <div class="brand-text">Rider Admin</div>
    </div>
    <div class="lang" style="flex-wrap:wrap;justify-content:flex-end">
      <a class="lang-btn <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"
         href="<?= e(BASE_PATH) ?>/admin">Dashboard</a>
      <a class="lang-btn <?= ($activePage ?? '') === 'applications' ? 'active' : '' ?>"
         href="<?= e(BASE_PATH) ?>/admin/applications">Başvurular</a>
      <a class="lang-btn <?= ($activePage ?? '') === 'cities' ? 'active' : '' ?>"
         href="<?= e(BASE_PATH) ?>/admin/cities">Şehirler</a>
      <a class="lang-btn <?= ($activePage ?? '') === 'vehicles' ? 'active' : '' ?>"
         href="<?= e(BASE_PATH) ?>/admin/vehicles">Araçlar</a>
      <a class="lang-btn <?= ($activePage ?? '') === 'users' ? 'active' : '' ?>"
         href="<?= e(BASE_PATH) ?>/admin/users">Adminler</a>
      <a class="lang-btn <?= ($activePage ?? '') === 'password' ? 'active' : '' ?>"
         href="<?= e(BASE_PATH) ?>/admin/settings/password">Şifre</a>
      <a class="lang-btn" href="<?= e(BASE_PATH) ?>/admin/logout">Çıkış</a>
    </div>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">

      <?php if (!empty($success)): ?>
        <div class="alert-success"><?= e($success) ?></div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="alert-danger"><?= e($error) ?></div>
      <?php endif; ?>

      <?= $content ?? '' ?>

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
