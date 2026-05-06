<?php
$_htmlDir = t('html.dir') === 'rtl' ? ' dir="rtl"' : '';
?>
<!doctype html>
<html lang="<?= e(t('html.lang')) ?>"<?= $_htmlDir ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kullanım Koşulları – Rider</title>
  <meta name="description" content="Rider platformu kullanım koşulları. Hizmetlerimizden yararlanmadan önce lütfen okuyunuz.">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23FFCD1A'/><text x='16' y='23' text-anchor='middle' font-size='18' font-weight='900' fill='%23188669' font-family='system-ui,sans-serif'>R</text></svg>">
  <link rel="stylesheet" href="<?= e(BASE_PATH) ?>/assets/app.css">
  <style>
    .legal-content{max-width:760px;margin:0 auto}
    .legal-content h2{font-size:18px;margin:28px 0 10px}
    .legal-content p,.legal-content li{color:var(--muted);font-size:15px;line-height:1.7;margin:0 0 10px}
    .legal-content ul{padding-left:20px}
  </style>
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
    <a class="lang-btn" href="<?= e(BASE_PATH) ?>/"><?= e(t('nav.home')) ?></a>
  </div>
</header>

<main>
  <section class="hero">
    <div class="container">
      <div class="card legal-content">
        <div class="card-pad">
          <h1 class="h1" style="font-size:28px">Kullanım Koşulları</h1>
          <p style="color:var(--muted);font-size:14px">Son güncelleme: <?= date('d.m.Y') ?></p>

          <h2>1. Kabul</h2>
          <p>Rider platformuna erişerek veya hizmetlerimizi kullanarak bu kullanım koşullarını kabul etmiş sayılırsınız. Koşulları kabul etmiyorsanız platformu kullanmayınız.</p>

          <h2>2. Hizmet Tanımı</h2>
          <p>Rider, işletmeler ile bağımsız kurye olarak çalışmak isteyen kişiler arasında bağlantı kuran bir teknoloji platformudur. Rider, doğrudan bir lojistik şirketi değil; aracı platform niteliğindedir.</p>

          <h2>3. Başvuru Koşulları</h2>
          <p>Başvuru yapabilmek için aşağıdaki koşulları karşılamanız gerekmektedir:</p>
          <ul>
            <li>18 yaşını doldurmuş olmak</li>
            <li>Türkiye Cumhuriyeti vatandaşı veya yasal çalışma iznine sahip olmak</li>
            <li>Geçerli kimlik belgesine sahip olmak</li>
            <li>Teslimat yapabilecek bir araca (bisiklet, scooter, motosiklet veya araç) sahip olmak</li>
          </ul>

          <h2>4. Kurye Yükümlülükleri</h2>
          <p>Onaylanan kuryeler aşağıdaki yükümlülüklere uymayı kabul eder:</p>
          <ul>
            <li>Müşterilere ve işletmelere saygılı davranmak</li>
            <li>Teslimatları zamanında ve eksiksiz gerçekleştirmek</li>
            <li>Uygulamada doğru konum bilgisini paylaşmak</li>
            <li>Trafik kurallarına ve yasa hükümlerine uymak</li>
            <li>Platform kurallarına aykırı davranışlardan kaçınmak</li>
          </ul>

          <h2>5. Ödeme Koşulları</h2>
          <p>Kurye ödemeleri her hafta Pazartesi günü, önceki haftanın tamamlanan teslimatları için hesaba aktarılır. Ödeme tutarı, teslimat adedi, mesafe ve bonus çarpanlarına göre belirlenir.</p>

          <h2>6. Hesap Askıya Alma ve Sonlandırma</h2>
          <p>Rider, aşağıdaki durumlarda kurye hesabını askıya alabilir veya sonlandırabilir:</p>
          <ul>
            <li>Kullanım koşullarının ihlali</li>
            <li>Müşteri şikayetlerinin belirli bir eşiği aşması</li>
            <li>Sahte belge veya bilgi sunulması</li>
            <li>Platforma zarar verecek davranışlar</li>
          </ul>

          <h2>7. Sorumluluk Sınırlaması</h2>
          <p>Rider, teslimat sürecinde kurye veya müşteri tarafından oluşturulan zararlardan doğrudan sorumlu tutulamaz. Platform, aracı hizmet sağlayıcı olarak yalnızca teknik altyapıyı sağlar.</p>

          <h2>8. Değişiklikler</h2>
          <p>Rider, bu kullanım koşullarını önceden bildirmeksizin değiştirme hakkını saklı tutar. Güncel koşullar her zaman bu sayfada yayımlanacaktır.</p>

          <h2>9. Uygulanacak Hukuk</h2>
          <p>Bu sözleşme Türkiye Cumhuriyeti hukukuna tabidir. Uyuşmazlıklarda İstanbul Mahkemeleri ve İcra Daireleri yetkilidir.</p>

          <h2>10. İletişim</h2>
          <p>Kullanım koşulları hakkında sorularınız için:<br>
          <strong>E-posta:</strong> <a href="mailto:destek@rider.com">destek@rider.com</a><br>
          <strong>Adres:</strong> Rider Teknoloji A.Ş., İstanbul, Türkiye</p>

          <div style="height:18px"></div>
          <a class="lang-btn" href="<?= e(BASE_PATH) ?>/"><?= e(t('terms.back_home')) ?></a>
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
