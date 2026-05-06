<?php
$_lang    = $_SESSION['lang'] ?? 'bs';
$_htmlDir = t('html.dir') === 'rtl' ? ' dir="rtl"' : '';
?>
<!doctype html>
<html lang="<?= e(t('html.lang')) ?>"<?= $_htmlDir ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(t('home.meta_title')) ?></title>
  <meta name="description" content="<?= e(t('home.meta_desc')) ?>">
  <meta property="og:title" content="<?= e(t('home.og_title')) ?>">
  <meta property="og:description" content="<?= e(t('home.og_desc')) ?>">
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
      <a class="lang-btn" href="<?= e(BASE_PATH) ?>/apply"><?= e(t('nav.apply')) ?></a>
    </div>
  </div>
</header>

<main>
  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <div class="grid">
        <div class="card">
          <div class="card-pad">
            <h1 class="h1"><?= e(t('home.hero_h1')) ?></h1>
            <p class="p"><?= e(t('home.hero_p')) ?></p>
            <div style="height:18px"></div>
            <a class="lang-btn" href="<?= e(BASE_PATH) ?>/apply"><?= e(t('home.apply_btn')) ?></a>
          </div>
        </div>

        <div class="card apply-card">
          <div class="card-pad">
            <h2><?= e(t('home.hero_card_h2')) ?></h2>
            <p style="margin:0 0 18px;opacity:.95"><?= e(t('home.hero_card_p')) ?></p>
            <a href="<?= e(BASE_PATH) ?>/apply" class="btn"><?= e(t('home.apply_btn')) ?></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats band -->
  <div class="stats-band">
    <div class="container">
      <div class="stat-item">
        <div class="stat-num">5.000+</div>
        <div class="stat-lbl"><?= e(t('home.stat_couriers')) ?></div>
      </div>
      <div class="stat-item">
        <div class="stat-num">250+</div>
        <div class="stat-lbl"><?= e(t('home.stat_cities')) ?></div>
      </div>
      <div class="stat-item">
        <div class="stat-num"><?= e(t('home.stat_payment')) ?></div>
        <div class="stat-lbl"><?= e(t('home.stat_pay_lbl')) ?></div>
      </div>
      <div class="stat-item">
        <div class="stat-num">4.8 ★</div>
        <div class="stat-lbl"><?= e(t('home.stat_rating')) ?></div>
      </div>
    </div>
  </div>

  <!-- How it works -->
  <section class="section">
    <div class="container">
      <h2 class="section-title"><?= e(t('home.how_title')) ?></h2>
      <p class="section-sub"><?= e(t('home.how_sub')) ?></p>
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-icon">📝</div>
          <div class="step-title"><?= e(t('home.step1_title')) ?></div>
          <p class="step-desc"><?= e(t('home.step1_desc')) ?></p>
        </div>
        <div class="step-card">
          <div class="step-icon">✅</div>
          <div class="step-title"><?= e(t('home.step2_title')) ?></div>
          <p class="step-desc"><?= e(t('home.step2_desc')) ?></p>
        </div>
        <div class="step-card">
          <div class="step-icon">🚴</div>
          <div class="step-title"><?= e(t('home.step3_title')) ?></div>
          <p class="step-desc"><?= e(t('home.step3_desc')) ?></p>
        </div>
        <div class="step-card">
          <div class="step-icon">💰</div>
          <div class="step-title"><?= e(t('home.step4_title')) ?></div>
          <p class="step-desc"><?= e(t('home.step4_desc')) ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Earnings band -->
  <div class="earnings-band">
    <div class="container">
      <div class="earnings-num">₺8.500+</div>
      <div class="earnings-text"><?= e(t('home.earnings_text')) ?><br><small style="font-weight:400;opacity:.7"><?= e(t('home.earnings_note')) ?></small></div>
    </div>
  </div>

  <!-- Benefits -->
  <section class="section">
    <div class="container">
      <h2 class="section-title"><?= e(t('home.why_title')) ?></h2>
      <p class="section-sub"><?= e(t('home.why_sub')) ?></p>
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon">⏰</div>
          <div>
            <div class="benefit-title"><?= e(t('home.b1_title')) ?></div>
            <p class="benefit-desc"><?= e(t('home.b1_desc')) ?></p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">💳</div>
          <div>
            <div class="benefit-title"><?= e(t('home.b2_title')) ?></div>
            <p class="benefit-desc"><?= e(t('home.b2_desc')) ?></p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">🛵</div>
          <div>
            <div class="benefit-title"><?= e(t('home.b3_title')) ?></div>
            <p class="benefit-desc"><?= e(t('home.b3_desc')) ?></p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">📱</div>
          <div>
            <div class="benefit-title"><?= e(t('home.b4_title')) ?></div>
            <p class="benefit-desc"><?= e(t('home.b4_desc')) ?></p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">🎁</div>
          <div>
            <div class="benefit-title"><?= e(t('home.b5_title')) ?></div>
            <p class="benefit-desc"><?= e(t('home.b5_desc')) ?></p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">🆘</div>
          <div>
            <div class="benefit-title"><?= e(t('home.b6_title')) ?></div>
            <p class="benefit-desc"><?= e(t('home.b6_desc')) ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="section" style="padding-top:0">
    <div class="container">
      <h2 class="section-title"><?= e(t('home.faq_title')) ?></h2>
      <p class="section-sub"><?= e(t('home.faq_sub')) ?></p>
      <div class="faq-list">
        <div class="faq-item">
          <button class="faq-q" type="button">
            <?= e(t('home.faq1_q')) ?>
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p><?= e(t('home.faq1_a')) ?></p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            <?= e(t('home.faq2_q')) ?>
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p><?= e(t('home.faq2_a')) ?></p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            <?= e(t('home.faq3_q')) ?>
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p><?= e(t('home.faq3_a')) ?></p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            <?= e(t('home.faq4_q')) ?>
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p><?= e(t('home.faq4_a')) ?></p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            <?= e(t('home.faq5_q')) ?>
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p><?= e(t('home.faq5_a')) ?></p></div>
        </div>
      </div>
    </div>
  </section>
</main>

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
        <p class="footer-brand-tagline"><?= t('footer.tagline') ?></p>
        <p style="font-size:12px;opacity:.55;margin:12px 0 0"><?= e(t('footer.company')) ?></p>
      </div>

      <div>
        <div class="footer-col-title"><?= e(t('footer.quick_links')) ?></div>
        <ul class="footer-links-list">
          <li><a href="<?= e(BASE_PATH) ?>/"><?= e(t('nav.home')) ?></a></li>
          <li><a href="<?= e(BASE_PATH) ?>/apply"><?= e(t('nav.apply')) ?></a></li>
          <li><a href="<?= e(BASE_PATH) ?>/privacy"><?= e(t('footer.privacy')) ?></a></li>
          <li><a href="<?= e(BASE_PATH) ?>/terms"><?= e(t('footer.terms')) ?></a></li>
          <li><a href="mailto:<?= e(t('footer.support_email')) ?>"><?= e(t('footer.support_email')) ?></a></li>
        </ul>
        <div style="margin-top:16px">
          <div class="footer-col-title"><?= e(t('footer.social')) ?></div>
          <div class="social-links">
            <a href="#" class="social-btn">📸 Instagram</a>
            <a href="#" class="social-btn">𝕏 Twitter</a>
            <a href="#" class="social-btn">in LinkedIn</a>
          </div>
        </div>
      </div>

      <div>
        <div class="footer-col-title"><?= e(t('footer.download')) ?></div>
        <div class="store-badges">
          <a href="#" class="store-badge">
            <span style="font-size:20px">🍎</span>
            <div>
              <div style="font-size:10px;opacity:.75;font-weight:400"><?= e(t('footer.appstore')) ?></div>
              <div>App Store</div>
            </div>
          </a>
          <a href="#" class="store-badge">
            <span style="font-size:20px">▶</span>
            <div>
              <div style="font-size:10px;opacity:.75;font-weight:400"><?= e(t('footer.playstore')) ?></div>
              <div>Google Play</div>
            </div>
          </a>
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
