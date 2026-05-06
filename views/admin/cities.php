<?php
$title      = 'Şehirler';
$activePage = 'cities';

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Şehirler</h1>
</div>

<!-- Add city form -->
<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:16px 20px">
    <form method="POST" action="<?= e(BASE_PATH) ?>/admin/cities"
          style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <?= csrf_field() ?>
      <div>
        <label class="label" for="city_name" style="font-size:.82rem">Yeni Şehir Ekle</label>
        <input class="input" type="text" id="city_name" name="name"
               placeholder="Şehir adı" required style="width:240px">
      </div>
      <button class="btn" type="submit" style="background:var(--green);color:#fff;width:auto;padding:0 20px">
        + Ekle
      </button>
    </form>
  </div>
</div>

<!-- Cities table -->
<div class="card" style="padding:0;overflow:hidden">
  <?php if (empty($cities)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Henüz şehir eklenmemiş.</p>
  <?php else: ?>
  <div style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Şehir Adı</th>
          <th style="width:80px">Sil</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($cities as $city): ?>
        <tr>
          <td><?= (int)$city['id'] ?></td>
          <td><?= e($city['name']) ?></td>
          <td>
            <form method="POST"
                  action="<?= e(BASE_PATH) ?>/admin/cities/<?= (int)$city['id'] ?>/delete"
                  onsubmit="return confirm('<?= e($city['name']) ?> silinsin mi?')">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-sm btn-danger">Sil</button>
            </form>
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
