<?php
declare(strict_types=1);
?>
<section class="hero">
  <div class="container">
    <div class="grid">
      <div class="card">
        <div class="card-pad">
          <h1 class="h1"><?= htmlspecialchars(t('hero_title')) ?></h1>
          <p class="p"><?= htmlspecialchars(t('hero_subtitle')) ?></p>
          <div style="height:18px"></div>
          <a class="lang-btn" href="<?= htmlspecialchars(BASE_PATH) ?>/apply"><?= htmlspecialchars(t('cta_apply')) ?></a>
        </div>
      </div>

      <div class="card apply-card">
        <div class="card-pad">
          <h2><?= htmlspecialchars(t('apply_box_title')) ?></h2>
          <p style="margin:0 0 18px;opacity:.95"><?= htmlspecialchars(t('apply_box_sub')) ?></p>
          <a href="<?= htmlspecialchars(BASE_PATH) ?>/apply" class="btn"><?= htmlspecialchars(t('cta_apply')) ?></a>
        </div>
      </div>
    </div>
  </div>
</section>