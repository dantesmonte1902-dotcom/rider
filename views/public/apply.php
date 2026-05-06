<?php
$_lang    = $_SESSION['lang'] ?? 'bs';
$_htmlDir = t('html.dir') === 'rtl' ? ' dir="rtl"' : '';
?>
<!doctype html>
<html lang="<?= e(t('html.lang')) ?>"<?= $_htmlDir ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(t('apply.meta_title')) ?></title>
  <meta name="description" content="<?= e(t('apply.meta_desc')) ?>">
  <meta property="og:title" content="<?= e(t('apply.og_title')) ?>">
  <meta property="og:description" content="<?= e(t('apply.og_desc')) ?>">
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
        <a href="<?= e(BASE_PATH) ?>/lang/bs"<?= $_lang === 'bs' ? ' class="active"' : '' ?>>BS</a>
        <a href="<?= e(BASE_PATH) ?>/lang/en"<?= $_lang === 'en' ? ' class="active"' : '' ?>>EN</a>
        <a href="<?= e(BASE_PATH) ?>/lang/tr"<?= $_lang === 'tr' ? ' class="active"' : '' ?>>TR</a>
        <a href="<?= e(BASE_PATH) ?>/lang/ar"<?= $_lang === 'ar' ? ' class="active"' : '' ?>>AR</a>
      </div>
      <a class="lang-btn" href="<?= e(BASE_PATH) ?>/"><?= e(t('nav.home')) ?></a>
    </div>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">
      <div class="grid">
        <div class="card">
          <div class="card-pad">
            <h1 class="h1"><?= e(t('apply.h1')) ?></h1>
            <p class="p"><?= e(t('apply.hero_p')) ?></p>
            <div style="height:18px"></div>
            <p class="p" style="font-size:14px"><?= e(t('apply.hero_p2')) ?></p>
          </div>
        </div>

        <div class="card apply-card" id="apply-form">
          <div class="card-pad">
            <h2><?= e(t('apply.card_h2')) ?></h2>

            <!-- Step indicator -->
            <div class="step-indicator">
              <div class="step-dot active" id="step-dot-1">1</div>
              <div class="step-line"></div>
              <span class="step-label"><?= e(t('apply.step1_label')) ?></span>
              <div class="step-line"></div>
              <div class="step-dot" id="step-dot-2">2</div>
              <div class="step-line"></div>
              <span class="step-label"><?= e(t('apply.step2_label')) ?></span>
            </div>

            <?php if (!empty($error)): ?>
              <div class="alert"><?= e(t($error)) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= e(BASE_PATH) ?>/apply" autocomplete="off">
              <?= csrf_field() ?>

              <!-- Honeypot – must be empty -->
              <input type="text" name="website" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">

              <!-- Step 1: Phone, City, Vehicle -->
              <div id="form-step-1" class="form-step">
                <div class="field">
                  <label class="label"><?= e(t('apply.field_phone')) ?></label>
                  <input class="input" type="tel" name="phone"
                         value="<?= e($_POST['phone'] ?? '') ?>"
                         placeholder="<?= e(t('apply.phone_ph')) ?>"
                         required>
                </div>

                <div class="field">
                  <label class="label"><?= e(t('apply.field_city')) ?></label>
                  <?php if (!empty($cities)): ?>
                    <select name="city" required>
                      <option value=""><?= e(t('apply.city_ph_sel')) ?></option>
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
                           placeholder="<?= e(t('apply.city_ph_type')) ?>" required>
                  <?php endif; ?>
                </div>

                <div class="field">
                  <label class="label"><?= e(t('apply.field_vehicle')) ?></label>
                  <?php if (!empty($vehicle_types)): ?>
                    <select name="vehicle_type" required>
                      <option value=""><?= e(t('apply.veh_ph_sel')) ?></option>
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
                           placeholder="<?= e(t('apply.veh_ph_type')) ?>" required>
                  <?php endif; ?>
                </div>

                <button class="btn" type="button" id="btn-next-step"
                        style="background:var(--yellow);color:var(--green);">
                  <?= e(t('apply.btn_next')) ?>
                </button>
              </div>

              <!-- Step 2: Personal info -->
              <div id="form-step-2" class="form-step hidden">
                <div class="field">
                  <label class="label"><?= e(t('apply.field_name')) ?></label>
                  <input class="input" type="text" name="name"
                         value="<?= e($_POST['name'] ?? '') ?>"
                         placeholder="<?= e(t('apply.name_ph')) ?>"
                         required>
                </div>

                <div class="field">
                  <label class="label"><?= e(t('apply.field_email')) ?></label>
                  <input class="input" type="email" name="email"
                         value="<?= e($_POST['email'] ?? '') ?>"
                         placeholder="<?= e(t('apply.email_ph')) ?>"
                         required>
                </div>

                <div class="field">
                  <label class="label"><?= e(t('apply.field_msg')) ?> <span style="opacity:.65;font-weight:400"><?= e(t('apply.optional')) ?></span></label>
                  <input class="input" type="text" name="message"
                         value="<?= e($_POST['message'] ?? '') ?>"
                         placeholder="<?= e(t('apply.msg_ph')) ?>">
                </div>

                <div class="field">
                  <label class="label"><?= e(t('apply.field_ref')) ?> <span style="opacity:.65;font-weight:400"><?= e(t('apply.optional')) ?></span></label>
                  <input class="input" type="text" name="referral_code"
                         value="<?= e($_POST['referral_code'] ?? '') ?>"
                         placeholder="<?= e(t('apply.ref_ph')) ?>"
                         maxlength="32">
                </div>

                <div class="field-check">
                  <input type="checkbox" name="kvkk" id="kvkk"
                         <?= !empty($_POST['kvkk']) ? 'checked' : '' ?> required>
                  <label for="kvkk">
                    <?= e(t('apply.kvkk')) ?>
                    <a href="<?= e(BASE_PATH) ?>/privacy" target="_blank"><?= e(t('apply.privacy_link')) ?></a>
                  </label>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap">
                  <button class="btn" type="button" id="btn-prev-step"
                          style="background:rgba(255,255,255,.2);color:#fff;flex:0 0 auto;width:auto;padding:0 20px">
                    <?= e(t('apply.btn_back')) ?>
                  </button>
                  <button class="btn" type="submit" style="flex:1"><?= e(t('apply.btn_submit')) ?></button>
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
    <?= e(t('apply.cta_btn')) ?>
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
        <p class="footer-brand-tagline"><?= t('footer.tagline_short') ?></p>
        <p style="font-size:12px;opacity:.55;margin:12px 0 0"><?= e(t('footer.company')) ?></p>
      </div>
      <div>
        <div class="footer-col-title"><?= e(t('footer.quick_links')) ?></div>
        <ul class="footer-links-list">
          <li><a href="<?= e(BASE_PATH) ?>/"><?= e(t('nav.home')) ?></a></li>
          <li><a href="<?= e(BASE_PATH) ?>/privacy"><?= e(t('footer.privacy')) ?></a></li>
          <li><a href="<?= e(BASE_PATH) ?>/terms"><?= e(t('footer.terms')) ?></a></li>
          <li><a href="mailto:<?= e(t('footer.support_email')) ?>"><?= e(t('footer.support_email')) ?></a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title"><?= e(t('footer.social')) ?></div>
        <div class="social-links">
          <a href="#" class="social-btn">📸 Instagram</a>
          <a href="#" class="social-btn">𝕏 Twitter</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div><?= e(sprintf(t('footer.copyright'), (int) date('Y'))) ?></div>
      <div style="display:flex;gap:14px">
        <a href="<?= e(BASE_PATH) ?>/privacy"><?= e(t('footer.privacy')) ?></a>
        <a href="<?= e(BASE_PATH) ?>/terms"><?= e(t('footer.terms')) ?></a>
      </div>
    </div>
  </div>
</footer>

<script src="<?= e(BASE_PATH) ?>/assets/app.js"></script>
</body>
</html>
