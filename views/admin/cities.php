<?php
$title      = 'Şehirler';
$activePage = 'cities';

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Şehirler</h1>
</div>

<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:16px 20px">
    <form method="POST" action="<?= e(BASE_PATH) ?>/admin/cities"
          style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <?= csrf_field() ?>
      <div>
        <label class="label" for="city_name" style="font-size:.82rem">Boşnakça (BS) <span style="color:red">*</span></label>
        <input class="input" type="text" id="city_name" name="name" placeholder="Sarajevo" required style="width:160px">
      </div>
      <div>
        <label class="label" for="city_name_en" style="font-size:.82rem">İngilizce (EN)</label>
        <input class="input" type="text" id="city_name_en" name="name_en" placeholder="Sarajevo" style="width:160px">
      </div>
      <div>
        <label class="label" for="city_name_tr" style="font-size:.82rem">Türkçe (TR)</label>
        <input class="input" type="text" id="city_name_tr" name="name_tr" placeholder="Saraybosna" style="width:160px">
      </div>
      <div>
        <label class="label" for="city_name_ar" style="font-size:.82rem">Arapça (AR)</label>
        <input class="input" type="text" id="city_name_ar" name="name_ar" placeholder="سراييفو" style="width:160px">
      </div>
      <button class="btn" type="submit" style="background:var(--green);color:#fff;width:auto;padding:0 20px">+ Ekle</button>
    </form>
  </div>
</div>

<div class="card" style="padding:0;overflow:hidden">
  <?php if (empty($cities)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Henüz şehir eklenmemiş.</p>
  <?php else: ?>
  <div style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>BS (Boşnakça)</th>
          <th>EN (İngilizce)</th>
          <th>TR (Türkçe)</th>
          <th>AR (Arapça)</th>
          <th style="width:190px">İşlem</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($cities as $city): ?>
        <?php $formId = 'city_edit_' . (int)$city['id']; ?>
        <tr>
            <td><?= (int)$city['id'] ?></td>
            <td><input class="input" type="text" name="name" value="<?= e($city['name']) ?>" required form="<?= e($formId) ?>"></td>
            <td><input class="input" type="text" name="name_en" value="<?= e($city['name_en'] ?? '') ?>" form="<?= e($formId) ?>"></td>
            <td><input class="input" type="text" name="name_tr" value="<?= e($city['name_tr'] ?? '') ?>" form="<?= e($formId) ?>"></td>
            <td><input class="input" type="text" name="name_ar" value="<?= e($city['name_ar'] ?? '') ?>" form="<?= e($formId) ?>"></td>
            <td>
              <div style="display:flex;gap:6px;flex-wrap:wrap">
                <form id="<?= e($formId) ?>" method="POST" action="<?= e(BASE_PATH) ?>/admin/cities/<?= (int)$city['id'] ?>/edit">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-success">Kaydet</button>
                </form>
                <form method="POST" action="<?= e(BASE_PATH) ?>/admin/cities/<?= (int)$city['id'] ?>/delete" onsubmit="return confirm('<?= e($city['name']) ?> silinsin mi?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </div>
            </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
