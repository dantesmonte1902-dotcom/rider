<?php
declare(strict_types=1);
?>
<section class="hero">
  <div class="container">
    <div class="card">
      <div class="card-pad">
        <h1 class="h1"><?= htmlspecialchars(t('success_title')) ?></h1>
        <p class="p"><?= htmlspecialchars(t('success_sub')) ?></p>
        <div style="height:18px"></div>
        <a class="lang-btn" href="<?= htmlspecialchars(BASE_PATH) ?>/"><?= htmlspecialchars(t('back_home')) ?></a>
      </div>
    </div>
  </div>
</section>