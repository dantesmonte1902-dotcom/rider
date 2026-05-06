<?php
declare(strict_types=1);
$error = v('error', null);
?>
<section class="hero">
  <div class="container">
    <div class="card">
      <div class="card-pad">
        <h1 class="h1"><?= htmlspecialchars(t('admin_login')) ?></h1>
        <?php if ($error): ?>
          <div style="background:#ffe9e9;border:1px solid #ffb3b3;padding:10px 12px;border-radius:12px;margin:12px 0;">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars(BASE_PATH) ?>/admin/login">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />
          <div class="field">
            <label class="label">Email</label>
            <input class="input" name="email" />
          </div>
          <div class="field">
            <label class="label"><?= htmlspecialchars(t('password')) ?></label>
            <input class="input" type="password" name="password" />
          </div>
          <button class="btn" type="submit"><?= htmlspecialchars(t('login')) ?></button>
        </form>
      </div>
    </div>
  </div>
</section>