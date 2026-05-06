<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Başvuru – Rider</title>
  <link rel="stylesheet" href="<?= e(BASE_PATH) ?>/assets/app.css">
</head>
<body>
<header class="topbar">
  <div class="container topbar-inner">
    <div class="brand">
      <div class="brand-badge">R</div>
      <div class="brand-text">Rider</div>
    </div>
    <a class="lang-btn" href="<?= e(BASE_PATH) ?>/">Ana Sayfa</a>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">
      <div class="grid">
        <div class="card">
          <div class="card-pad">
            <h1 class="h1">Kurye başvurusu</h1>
            <p class="p">Temel bilgileri girin; ekibimiz sizinle iletişime geçecek.</p>
            <div style="height:18px"></div>
            <p class="p" style="font-size:14px">Evrak ve onboarding ilk görüşmeden sonra.</p>
          </div>
        </div>

        <div class="card apply-card" id="apply-form">
          <div class="card-pad">
            <h2>Profil oluştur</h2>

            <?php if (!empty($error)): ?>
              <div class="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= e(BASE_PATH) ?>/apply" autocomplete="off">
              <?= csrf_field() ?>

              <div class="field">
                <label class="label">Ad Soyad</label>
                <input class="input" type="text" name="name"
                       value="<?= e($_POST['name'] ?? '') ?>"
                       placeholder="Adınız Soyadınız">
              </div>

              <div class="field">
                <label class="label">E-posta</label>
                <input class="input" type="email" name="email"
                       value="<?= e($_POST['email'] ?? '') ?>"
                       placeholder="ornek@email.com">
              </div>

              <div class="field">
                <label class="label">Telefon</label>
                <input class="input" type="tel" name="phone"
                       value="<?= e($_POST['phone'] ?? '') ?>"
                       placeholder="05XX XXX XX XX">
              </div>

              <div class="field">
                <label class="label">Şehir</label>
                <?php if (!empty($cities)): ?>
                  <select name="city">
                    <option value="">Şehir seçin</option>
                    <?php foreach ($cities as $c): ?>
                      <option value="<?= e($c['name']) ?>"
                        <?= ($_POST['city'] ?? '') === $c['name'] ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php else: ?>
                  <input class="input" type="text" name="city"
                         value="<?= e($_POST['city'] ?? '') ?>"
                         placeholder="Şehrinizi yazın">
                <?php endif; ?>
              </div>

              <div class="field">
                <label class="label">Araç Tipi</label>
                <?php if (!empty($vehicle_types)): ?>
                  <select name="vehicle_type">
                    <option value="">Araç seçin</option>
                    <?php foreach ($vehicle_types as $v): ?>
                      <option value="<?= e($v['name']) ?>"
                        <?= ($_POST['vehicle_type'] ?? '') === $v['name'] ? 'selected' : '' ?>>
                        <?= e($v['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php else: ?>
                  <input class="input" type="text" name="vehicle_type"
                         value="<?= e($_POST['vehicle_type'] ?? '') ?>"
                         placeholder="Araç tipi">
                <?php endif; ?>
              </div>

              <div class="field">
                <label class="label">Notunuz</label>
                <input class="input" type="text" name="message"
                       value="<?= e($_POST['message'] ?? '') ?>"
                       placeholder="İsteğe bağlı not">
              </div>

              <button class="btn" type="submit">Gönder</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<div class="mobile-cta">
  <button class="btn" type="button" data-cta="scroll-to-form"
          style="background:var(--green);color:var(--yellow);">
    Hemen başvur
  </button>
</div>

<footer class="footer">
  <div class="container footer-inner">
    <div>Yardım: <a href="#" class="footer-link">Kurye web sitesi</a></div>
    <div class="muted">&copy; <?= date('Y') ?></div>
  </div>
</footer>

<script src="<?= e(BASE_PATH) ?>/assets/app.js"></script>
</body>
</html>
