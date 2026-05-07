<?php
$title      = 'Şifre Değiştir';
$activePage = 'password';

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Şifre Değiştir</h1>
</div>

<div class="card">
  <div class="card-pad" style="max-width:520px">
    <form method="POST" action="<?= e(BASE_PATH) ?>/admin/settings/password">
      <?= csrf_field() ?>
      <div class="field">
        <label class="label" for="current_password">Mevcut Şifre</label>
        <input class="input" type="password" id="current_password" name="current_password" required>
      </div>
      <div class="field">
        <label class="label" for="new_password">Yeni Şifre</label>
        <input class="input" type="password" id="new_password" name="new_password" minlength="8" required>
      </div>
      <div class="field">
        <label class="label" for="new_password_confirm">Yeni Şifre (Tekrar)</label>
        <input class="input" type="password" id="new_password_confirm" name="new_password_confirm" minlength="8" required>
      </div>
      <button class="btn" type="submit" style="background:var(--green);color:#fff;width:auto;padding:0 20px">Güncelle</button>
    </form>
  </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
