<?php
// This view is rendered via the admin layout helper.
// Collect content, then include layout.

$title      = 'Başvurular';
$activePage = 'applications';

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Sürücü Başvuruları</h1>
</div>

<?php if (!empty($db_error)): ?>
  <div class="alert-danger">Veritabanı hatası: <?= e($db_error) ?></div>
<?php endif; ?>

<!-- Filter bar -->
<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:16px 20px">
    <form method="GET" action="<?= e(BASE_PATH) ?>/admin/applications"
          style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <div>
        <label class="label" for="search" style="font-size:.82rem">Ara</label>
        <input class="input" type="text" id="search" name="search"
               value="<?= e($filter_search) ?>"
               placeholder="Ad, e-posta, şehir..."
               style="width:220px">
      </div>
      <div>
        <label class="label" for="status" style="font-size:.82rem">Durum</label>
        <select id="status" name="status" style="width:160px">
          <option value="">Tümü</option>
          <option value="pending"  <?= $filter_status === 'pending'  ? 'selected' : '' ?>>Bekliyor</option>
          <option value="approved" <?= $filter_status === 'approved' ? 'selected' : '' ?>>Onaylandı</option>
          <option value="rejected" <?= $filter_status === 'rejected' ? 'selected' : '' ?>>Reddedildi</option>
        </select>
      </div>
      <button class="lang-btn" type="submit">Filtrele</button>
      <?php if ($filter_status !== '' || $filter_search !== ''): ?>
        <a href="<?= e(BASE_PATH) ?>/admin/applications" class="lang-btn">Temizle</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Applications table -->
<div class="card" style="padding:0;overflow:hidden">
  <?php if (empty($applications)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Başvuru bulunamadı.</p>
  <?php else: ?>
  <div style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Ad Soyad</th>
          <th>E-posta</th>
          <th>Telefon</th>
          <th>Şehir</th>
          <th>Araç</th>
          <th>Tarih</th>
          <th>Durum</th>
          <th>İşlem</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($applications as $app): ?>
        <tr>
          <td><?= e($app['id']) ?></td>
          <td><?= e($app['name']) ?></td>
          <td><a href="mailto:<?= e($app['email']) ?>"><?= e($app['email']) ?></a></td>
          <td><?= e($app['phone']) ?></td>
          <td><?= e($app['city']) ?></td>
          <td><?= e($app['vehicle_type']) ?></td>
          <td><?= e(date('d.m.Y H:i', strtotime($app['created_at']))) ?></td>
          <td>
            <?php
            $statusLabels = ['pending' => 'Bekliyor', 'approved' => 'Onaylandı', 'rejected' => 'Reddedildi'];
            $statusClass  = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
            $s = $app['status'];
            ?>
            <span class="badge <?= e($statusClass[$s] ?? '') ?>"><?= e($statusLabels[$s] ?? $s) ?></span>
          </td>
          <td>
            <form method="POST"
                  action="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>/status"
                  style="display:inline;">
              <?= csrf_field() ?>
              <?php if ($s !== 'approved'): ?>
                <button type="submit" name="status" value="approved"
                        class="btn btn-sm btn-success">Onayla</button>
              <?php endif; ?>
              <?php if ($s !== 'rejected'): ?>
                <button type="submit" name="status" value="rejected"
                        class="btn btn-sm btn-danger">Reddet</button>
              <?php endif; ?>
              <?php if ($s !== 'pending'): ?>
                <button type="submit" name="status" value="pending"
                        class="btn btn-sm btn-warning">Beklet</button>
              <?php endif; ?>
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

// Inject into layout
require __DIR__ . '/layout.php';
