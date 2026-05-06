<?php
$_lang    = $_SESSION['lang'] ?? 'bs';
$_htmlDir = t('html.dir') === 'rtl' ? ' dir="rtl"' : '';
?>
<!doctype html>
<html lang="<?= e(t('html.lang')) ?>"<?= $_htmlDir ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(t('success.meta_title')) ?></title>
  <meta name="description" content="<?= e(t('success.meta_desc')) ?>">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23FFCD1A'/><text x='16' y='23' text-anchor='middle' font-size='18' font-weight='900' fill='%23188669' font-family='system-ui,sans-serif'>R</text></svg>">
  <link rel="stylesheet" href="<?= e(BASE_PATH) ?>/assets/app.css">
</head>
<body>
<header class="topbar">
  <div class="container topbar-inner">
    <div class="brand">
      <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect width="34" height="34" rx="10" fill="#FFCD1A"/>
        <text x="17" y="24" text-anchor="middle" font-size="19" font-weight="900" fill="#188669" font-family="system-ui,sans-serif">R</text>
      </svg>
      <div class="brand-text">Rider</div>
    </div>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">
      <div class="card" style="max-width:580px;margin:0 auto">
        <div class="card-pad" style="text-align:center">
          <div style="font-size:56px;margin-bottom:12px">🎉</div>
          <h1 class="h1"><?= e(t('success.h1')) ?></h1>
          <p class="p"><?= t('success.p') ?></p>
          <div style="height:18px"></div>
          <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
            <a class="lang-btn" href="<?= e(BASE_PATH) ?>/"><?= e(t('success.back_btn')) ?></a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="border-top:none;padding-top:28px;padding-bottom:28px">
      <div><?= e(sprintf(t('footer.copyright'), (int) date('Y'))) ?></div>
      <div style="display:flex;gap:14px">
        <a href="<?= e(BASE_PATH) ?>/privacy"><?= e(t('footer.privacy')) ?></a>
        <a href="<?= e(BASE_PATH) ?>/terms"><?= e(t('footer.terms')) ?></a>
      </div>
    </div>
  </div>
</footer>
</body>
</html>
