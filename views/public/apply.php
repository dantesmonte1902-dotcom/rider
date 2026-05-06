<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Başvuru – Rider | Kurye Ol</title>
  <meta name="description" content="Rider kurye başvuru formu. Ad, iletişim ve araç bilgilerini girerek hemen başvur, ekibimiz seninle iletişime geçsin.">
  <meta property="og:title" content="Rider Kurye Başvurusu">
  <meta property="og:description" content="Birkaç dakikada Rider kurye başvurunu tamamla.">
  <meta property="og:type" content="website">
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
    <div style="display:flex;align-items:center;gap:12px">
      <div class="lang-switch">
        <a href="<?= e(BASE_PATH) ?>/lang/tr" class="active">TR</a>
        <a href="<?= e(BASE_PATH) ?>/lang/en">EN</a>
      </div>
      <a class="lang-btn" href="<?= e(BASE_PATH) ?>/">Ana Sayfa</a>
    </div>
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

            <!-- Step indicator -->
            <div class="step-indicator">
              <div class="step-dot active" id="step-dot-1">1</div>
              <div class="step-line"></div>
              <span class="step-label">Konum &amp; Araç</span>
              <div class="step-line"></div>
              <div class="step-dot" id="step-dot-2">2</div>
              <div class="step-line"></div>
              <span class="step-label">Kişisel Bilgi</span>
            </div>

            <?php if (!empty($error)): ?>
              <div class="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= e(BASE_PATH) ?>/apply" autocomplete="off">
              <?= csrf_field() ?>

              <!-- Honeypot – must be empty -->
              <input type="text" name="website" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">

              <!-- Step 1: Phone, City, Vehicle -->
              <div id="form-step-1" class="form-step">
                <div class="field">
                  <label class="label">Telefon</label>
                  <input class="input" type="tel" name="phone"
                         value="<?= e($_POST['phone'] ?? '') ?>"
                         placeholder="0532 123 45 67"
                         required>
                </div>

                <div class="field">
                  <label class="label">Şehir</label>
                  <?php if (!empty($cities)): ?>
                    <select name="city" required>
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
                           placeholder="Şehrinizi yazın" required>
                  <?php endif; ?>
                </div>

                <div class="field">
                  <label class="label">Araç Tipi</label>
                  <?php if (!empty($vehicle_types)): ?>
                    <select name="vehicle_type" required>
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
                           placeholder="Araç tipi" required>
                  <?php endif; ?>
                </div>

                <button class="btn" type="button" id="btn-next-step"
                        style="background:var(--yellow);color:var(--green);">
                  İleri →
                </button>
              </div>

              <!-- Step 2: Personal info -->
              <div id="form-step-2" class="form-step hidden">
                <div class="field">
                  <label class="label">Ad Soyad</label>
                  <input class="input" type="text" name="name"
                         value="<?= e($_POST['name'] ?? '') ?>"
                         placeholder="Adınız Soyadınız"
                         required>
                </div>

                <div class="field">
                  <label class="label">E-posta</label>
                  <input class="input" type="email" name="email"
                         value="<?= e($_POST['email'] ?? '') ?>"
                         placeholder="ornek@email.com"
                         required>
                </div>

                <div class="field">
                  <label class="label">Notunuz <span style="opacity:.65;font-weight:400">(isteğe bağlı)</span></label>
                  <input class="input" type="text" name="message"
                         value="<?= e($_POST['message'] ?? '') ?>"
                         placeholder="Eklemek istediğiniz bir şey varsa...">
                </div>

                <div class="field">
                  <label class="label">Davet Kodu <span style="opacity:.65;font-weight:400">(isteğe bağlı)</span></label>
                  <input class="input" type="text" name="referral_code"
                         value="<?= e($_POST['referral_code'] ?? '') ?>"
                         placeholder="Varsa davet kodunuzu girin"
                         maxlength="32">
                </div>

                <div class="field-check">
                  <input type="checkbox" name="kvkk" id="kvkk"
                         <?= !empty($_POST['kvkk']) ? 'checked' : '' ?> required>
                  <label for="kvkk">
                    Kişisel verilerimin Rider tarafından işlenmesini kabul ediyorum.
                    <a href="<?= e(BASE_PATH) ?>/privacy" target="_blank">Gizlilik Politikası</a>
                  </label>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap">
                  <button class="btn" type="button" id="btn-prev-step"
                          style="background:rgba(255,255,255,.2);color:#fff;flex:0 0 auto;width:auto;padding:0 20px">
                    ← Geri
                  </button>
                  <button class="btn" type="submit" style="flex:1">Gönder</button>
                </div>
              </div>

              <?php if (!empty($_POST)): ?>
                <script>
                  // If form was submitted with errors, show step 2 if step-1 fields are present
                  document.addEventListener('DOMContentLoaded', function() {
                    var s1 = document.getElementById('form-step-1');
                    var s2 = document.getElementById('form-step-2');
                    var d1 = document.getElementById('step-dot-1');
                    var d2 = document.getElementById('step-dot-2');
                    if (s1 && s2) {
                      s1.classList.add('hidden');
                      s2.classList.remove('hidden');
                      if (d1) { d1.classList.remove('active'); d1.classList.add('done'); }
                      if (d2) d2.classList.add('active');
                    }
                  });
                </script>
              <?php endif; ?>
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
  <div class="container">
    <div class="footer-cols">
      <div>
        <div class="brand" style="margin-bottom:10px">
          <svg width="30" height="30" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect width="34" height="34" rx="10" fill="#FFCD1A"/>
            <text x="17" y="24" text-anchor="middle" font-size="19" font-weight="900" fill="#188669" font-family="system-ui,sans-serif">R</text>
          </svg>
          <span style="font-weight:800;color:#fff">Rider</span>
        </div>
        <p class="footer-brand-tagline">Esnek çalış, hızlı kazan.</p>
        <p style="font-size:12px;opacity:.55;margin:12px 0 0">Rider Teknoloji A.Ş. · İstanbul, Türkiye</p>
      </div>
      <div>
        <div class="footer-col-title">Hızlı Linkler</div>
        <ul class="footer-links-list">
          <li><a href="<?= e(BASE_PATH) ?>/">Ana Sayfa</a></li>
          <li><a href="<?= e(BASE_PATH) ?>/privacy">Gizlilik Politikası</a></li>
          <li><a href="<?= e(BASE_PATH) ?>/terms">Kullanım Koşulları</a></li>
          <li><a href="mailto:destek@rider.com">destek@rider.com</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Sosyal Medya</div>
        <div class="social-links">
          <a href="#" class="social-btn">📸 Instagram</a>
          <a href="#" class="social-btn">𝕏 Twitter</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div>© <?= date('Y') ?> Rider Teknoloji A.Ş.</div>
      <div style="display:flex;gap:14px">
        <a href="<?= e(BASE_PATH) ?>/privacy">Gizlilik Politikası</a>
        <a href="<?= e(BASE_PATH) ?>/terms">Kullanım Koşulları</a>
      </div>
    </div>
  </div>
</footer>

<script src="<?= e(BASE_PATH) ?>/assets/app.js"></script>
</body>
</html>
