<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kurye Ol | Rider – Esnek Çalış, Hızlı Kazan</title>
  <meta name="description" content="Rider kurye platformuna katıl. Kendi programını belirle, bisiklet veya motosikletinle esnek çalış, haftalık ödeme al.">
  <meta property="og:title" content="Rider – Kurye Ol, Esnek Kazan">
  <meta property="og:description" content="Rider kurye platformuna katıl. Kendi saatlerinde çalış, haftalık ödeme al.">
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
      <?php $activeLang = $_SESSION['lang'] ?? 'bs'; ?>
      <div class="lang-switch">
        <a href="<?= e(BASE_PATH) ?>/lang/bs"<?= $activeLang === 'bs' ? ' class="active"' : '' ?>>BS</a>
        <a href="<?= e(BASE_PATH) ?>/lang/en"<?= $activeLang === 'en' ? ' class="active"' : '' ?>>EN</a>
        <a href="<?= e(BASE_PATH) ?>/lang/tr"<?= $activeLang === 'tr' ? ' class="active"' : '' ?>>TR</a>
        <a href="<?= e(BASE_PATH) ?>/lang/ar"<?= $activeLang === 'ar' ? ' class="active"' : '' ?>>AR</a>
      </div>
      <a class="lang-btn" href="<?= e(BASE_PATH) ?>/apply">Başvur</a>
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
            <h1 class="h1">Kurye olun 🛵</h1>
            <p class="p">Esnek çalışma saatleri, haftalık kazanç ve kendi kurallarınla çalışma özgürlüğü.</p>
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

  <!-- Stats band -->
  <div class="stats-band">
    <div class="container">
      <div class="stat-item">
        <div class="stat-num">5.000+</div>
        <div class="stat-lbl">Aktif Kurye</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">250+</div>
        <div class="stat-lbl">Hizmet Verilen Şehir</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">Haftalık</div>
        <div class="stat-lbl">Ödeme Güvencesi</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">4.8 ★</div>
        <div class="stat-lbl">Kurye Memnuniyeti</div>
      </div>
    </div>
  </div>

  <!-- How it works -->
  <section class="section">
    <div class="container">
      <h2 class="section-title">Nasıl Çalışır?</h2>
      <p class="section-sub">4 kolay adımda kurye ol ve kazanmaya başla</p>
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-icon">📝</div>
          <div class="step-title">1. Kaydol</div>
          <p class="step-desc">Kısa başvuru formunu doldur. Birkaç dakika sürer.</p>
        </div>
        <div class="step-card">
          <div class="step-icon">✅</div>
          <div class="step-title">2. Onaylan</div>
          <p class="step-desc">Ekibimiz belgelerini inceler ve sana geri döner.</p>
        </div>
        <div class="step-card">
          <div class="step-icon">🚴</div>
          <div class="step-title">3. Teslim Et</div>
          <p class="step-desc">Uygulamayı aç, siparişleri kabul et, teslimat yap.</p>
        </div>
        <div class="step-card">
          <div class="step-icon">💰</div>
          <div class="step-title">4. Kazan</div>
          <p class="step-desc">Kazancın her hafta otomatik olarak hesabına yatırılır.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Earnings band -->
  <div class="earnings-band">
    <div class="container">
      <div class="earnings-num">₺8.500+</div>
      <div class="earnings-text">Kuryelerimizin haftalık ortalama kazancı*<br><small style="font-weight:400;opacity:.7">*Yoğun sezon ve şehir merkezlerinde, tam zamanlı çalışma baz alınmıştır.</small></div>
    </div>
  </div>

  <!-- Benefits -->
  <section class="section">
    <div class="container">
      <h2 class="section-title">Neden Rider?</h2>
      <p class="section-sub">Binlerce kurye Rider ile çalışmayı tercih ediyor</p>
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon">⏰</div>
          <div>
            <div class="benefit-title">Esnek Saatler</div>
            <p class="benefit-desc">İstediğin zaman çalış. Sabah, öğle veya gece — sen karar verirsin.</p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">💳</div>
          <div>
            <div class="benefit-title">Haftalık Ödeme</div>
            <p class="benefit-desc">Kazancın her hafta otomatik olarak banka hesabına aktarılır.</p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">🛵</div>
          <div>
            <div class="benefit-title">Kendi Aracınla</div>
            <p class="benefit-desc">Bisiklet, scooter veya motosikletinle — özel araç şartı yok.</p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">📱</div>
          <div>
            <div class="benefit-title">Kolay Uygulama</div>
            <p class="benefit-desc">Kullanımı kolay mobil uygulama ile tüm işlemleri tek yerden yönet.</p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">🎁</div>
          <div>
            <div class="benefit-title">Performans Bonusu</div>
            <p class="benefit-desc">Yoğun saatlerde ve özel günlerde ekstra bonus kazan.</p>
          </div>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">🆘</div>
          <div>
            <div class="benefit-title">7/24 Destek</div>
            <p class="benefit-desc">Her an ulaşabileceğin destek ekibiyle yalnız değilsin.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="section" style="padding-top:0">
    <div class="container">
      <h2 class="section-title">Sıkça Sorulan Sorular</h2>
      <p class="section-sub">Aklındaki soruların cevabı burada</p>
      <div class="faq-list">
        <div class="faq-item">
          <button class="faq-q" type="button">
            Aracım olması gerekiyor mu?
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p>Evet, teslimat yapabilmek için bisiklet, elektrikli scooter, motosiklet veya otomobilinizin olması gerekiyor. Araç kiralama seçenekleri için destek ekibimizle iletişime geçebilirsiniz.</p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            Ne zaman ödeme alırım?
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p>Ödemeler her hafta Pazartesi günü hesabınıza otomatik olarak aktarılır. Minimum ödeme tutarı yoktur; kazandığınız her kuruş size ödenir.</p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            Hangi şehirlerde çalışabiliyorum?
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p>Şu an İstanbul, Ankara, İzmir başta olmak üzere 250'den fazla şehirde hizmet veriyoruz. Başvuru formunda şehrinizi seçerek hizmet bölgenizi öğrenebilirsiniz.</p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            Başvuru sonrası ne kadar beklerim?
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p>Başvurunuz genellikle 24–48 saat içinde değerlendirilir. Ekibimiz telefon veya e-posta yoluyla sizinle iletişime geçer.</p></div>
        </div>
        <div class="faq-item">
          <button class="faq-q" type="button">
            Hangi belgeler gerekli?
            <span class="chevron">▼</span>
          </button>
          <div class="faq-a"><p>Kimlik belgesi, araç ruhsatı (varsa), sürücü belgesi ve banka hesap bilgisi gereklidir. Detaylı belge listesi onay sonrasında paylaşılacaktır.</p></div>
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
        <p class="footer-brand-tagline">Esnek çalış, hızlı kazan.<br>Kendi kurallarınla sahaya çık.</p>
        <p style="font-size:12px;opacity:.55;margin:12px 0 0">Rider Teknoloji A.Ş. · İstanbul, Türkiye</p>
      </div>

      <div>
        <div class="footer-col-title">Hızlı Linkler</div>
        <ul class="footer-links-list">
          <li><a href="<?= e(BASE_PATH) ?>/">Ana Sayfa</a></li>
          <li><a href="<?= e(BASE_PATH) ?>/apply">Başvur</a></li>
          <li><a href="<?= e(BASE_PATH) ?>/privacy">Gizlilik Politikası</a></li>
          <li><a href="<?= e(BASE_PATH) ?>/terms">Kullanım Koşulları</a></li>
          <li><a href="mailto:destek@rider.com">destek@rider.com</a></li>
        </ul>
        <div style="margin-top:16px">
          <div class="footer-col-title">Sosyal Medya</div>
          <div class="social-links">
            <a href="#" class="social-btn">📸 Instagram</a>
            <a href="#" class="social-btn">𝕏 Twitter</a>
            <a href="#" class="social-btn">in LinkedIn</a>
          </div>
        </div>
      </div>

      <div>
        <div class="footer-col-title">Uygulamayı İndir</div>
        <div class="store-badges">
          <a href="#" class="store-badge">
            <span style="font-size:20px">🍎</span>
            <div>
              <div style="font-size:10px;opacity:.75;font-weight:400">App Store'da İndir</div>
              <div>App Store</div>
            </div>
          </a>
          <a href="#" class="store-badge">
            <span style="font-size:20px">▶</span>
            <div>
              <div style="font-size:10px;opacity:.75;font-weight:400">Google Play'den İndir</div>
              <div>Google Play</div>
            </div>
          </a>
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
