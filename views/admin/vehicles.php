<?php
$title      = 'Araç Tipleri';
$activePage = 'vehicles';

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Araç Tipleri</h1>
</div>

<!-- Add vehicle type form -->
<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:16px 20px">
    <form method="POST" action="<?= e(BASE_PATH) ?>/admin/vehicles"
          style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <?= csrf_field() ?>
      <div>
        <label class="label" for="vehicle_name" style="font-size:.82rem">Yeni Araç Tipi Ekle</label>
        <input class="input" type="text" id="vehicle_name" name="name"
               placeholder="Araç tipi adı" required style="width:240px">
      </div>
      <button class="btn" type="submit" style="background:var(--green);color:#fff;width:auto;padding:0 20px">
        + Ekle
      </button>
    </form>
  </div>
</div>

<!-- Vehicle types table -->
<div class="card" style="padding:0;overflow:hidden">
  <?php if (empty($vehicle_types)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Henüz araç tipi eklenmemiş.</p>
  <?php else: ?>
  <div style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Araç Tipi</th>
          <th style="width:80px">Sil</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($vehicle_types as $vt): ?>
        <tr>
          <td><?= (int)$vt['id'] ?></td>
          <td><?= e($vt['name']) ?></td>
          <td>
            <form method="POST"
                  action="<?= e(BASE_PATH) ?>/admin/vehicles/<?= (int)$vt['id'] ?>/delete"
                  onsubmit="return confirm('<?= e($vt['name']) ?> silinsin mi?')">
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
