<?php
declare(strict_types=1);

$view = (string)($GLOBALS['__view_name'] ?? 'home');
$lang = current_locale();

function v($key, $default = null) {
    $data = (array)($GLOBALS['__view_data'] ?? []);
    return $data[$key] ?? $default;
}
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang) ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars(t('title')) ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars(BASE_PATH) ?>/assets/app.css?v=1" />
</head>
<body>
  <header class="topbar">
    <div class="container topbar-inner">
      <div class="brand">
        <div class="brand-badge">G</div>
        <div class="brand-text"><?= htmlspecialchars(t('brand')) ?></div>
      </div>
      <div class="lang">
        <?php foreach (supported_locales() as $l): ?>
          <a class="lang-btn <?= $l === $lang ? 'active' : '' ?>" href="<?= htmlspecialchars(BASE_PATH) ?>/?lang=<?= $l ?>"><?= $l ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </header>

  <main>
    <?php require __DIR__ . '/' . $view . '.php'; ?>
  </main>

  <footer class="footer">
    <div class="container footer-inner">
      <div><?= htmlspecialchars(t('footer_help')) ?>: <a href="#" class="footer-link"><?= htmlspecialchars(t('footer_rider_site')) ?></a></div>
      <div class="muted"><?= htmlspecialchars(t('footer_note')) ?></div>
    </div>
  </footer>

  <script src="<?= htmlspecialchars(BASE_PATH) ?>/assets/app.js?v=1"></script>
</body>
</html>