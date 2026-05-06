<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kurye Ol | Rider</title>
  <link rel="stylesheet" href="<?= e(BASE_PATH) ?>/assets/app.css">
</head>
<body>
<header class="topbar">
  <div class="container topbar-inner">
    <div class="brand">
      <div class="brand-badge">R</div>
      <div class="brand-text">Rider</div>
    </div>
    <a class="lang-btn" href="<?= e(BASE_PATH) ?>/apply">Başvur</a>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">
      <div class="grid">
        <div class="card">
          <div class="card-pad">
            <h1 class="h1">Kurye olun</h1>
            <p class="p">Esnek çalışma, haftalık kazanç ve kendi kurallarınla çalışma özgürlüğü.</p>
            <div style="height:18px"></div>
            <a class="lang-btn" href="<?= e(BASE_PATH) ?>/apply">Hemen başvur</a>
          </div>
        </div>

        <div class="card apply-card">
          <div class="card-pad">
            <h2>Profil oluştur</h2>
            <p style="margin:0 0 18px;opacity:.95">Kısa formu doldurun, ekibimiz sizinle iletişime geçsin.</p>
            <a href="<?= e(BASE_PATH) ?>/apply" class="btn">Hemen başvur</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="container footer-inner">
    <div>Yardım: <a href="#" class="footer-link">Kurye web sitesi</a></div>
    <div class="muted">&copy; <?= date('Y') ?></div>
  </div>
</footer>

<script src="<?= e(BASE_PATH) ?>/assets/app.js"></script>
</body>
</html>
