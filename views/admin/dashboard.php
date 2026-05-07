<?php
$title      = 'Dashboard';
$activePage = 'dashboard';

$summary = $summary ?? [];
$total          = (int)($summary['total'] ?? 0);
$pending_count  = (int)($summary['pending_count'] ?? 0);
$approved_count = (int)($summary['approved_count'] ?? 0);
$rejected_count = (int)($summary['rejected_count'] ?? 0);
$week_count     = (int)($summary['week_count'] ?? 0);

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Dashboard</h1>
  <a class="lang-btn" href="<?= e(BASE_PATH) ?>/admin/applications">Başvurulara Git</a>
</div>

<?php if (!empty($db_error)): ?>
  <div class="alert-danger">Veritabanı hatası: <?= e($db_error) ?></div>
<?php endif; ?>

<div class="admin-stats-grid" style="margin-bottom:18px">
  <div class="admin-stat-card">
    <div class="admin-stat-label">Toplam Başvuru</div>
    <div class="admin-stat-value"><?= $total ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Bekleyen</div>
    <div class="admin-stat-value"><?= $pending_count ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Onaylanan</div>
    <div class="admin-stat-value"><?= $approved_count ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Reddedilen</div>
    <div class="admin-stat-value"><?= $rejected_count ?></div>
  </div>
  <div class="admin-stat-card">
    <div class="admin-stat-label">Son 7 Gün</div>
    <div class="admin-stat-value"><?= $week_count ?></div>
  </div>
</div>

<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="display:flex;gap:16px;flex-wrap:wrap;align-items:center;justify-content:space-between">
    <div>
      <div class="label" style="margin-bottom:4px">En çok başvuru gelen şehir</div>
      <div style="font-weight:800;font-size:1.08rem">
        <?= !empty($top_city['city']) ? e($top_city['city']) . ' (' . (int)$top_city['cnt'] . ')' : '—' ?>
      </div>
    </div>
    <div>
      <div class="label" style="margin-bottom:4px">En çok başvuru gelen araç tipi</div>
      <div style="font-weight:800;font-size:1.08rem">
        <?= !empty($top_vehicle['vehicle_type']) ? e($top_vehicle['vehicle_type']) . ' (' . (int)$top_vehicle['cnt'] . ')' : '—' ?>
      </div>
    </div>
  </div>
</div>

<div class="card" style="padding:0;overflow:hidden">
  <div class="card-pad" style="padding:16px 20px;border-bottom:1px solid #ecf0f1">
    <strong>Son Başvurular</strong>
  </div>
  <?php if (empty($recent)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Henüz başvuru yok.</p>
  <?php else: ?>
    <div style="overflow:auto">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Ad Soyad</th>
            <th>Şehir</th>
            <th>Araç</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th>Detay</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $statusLabels = ['pending' => 'Bekliyor', 'approved' => 'Onaylandı', 'rejected' => 'Reddedildi'];
        $statusClass  = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
        foreach ($recent as $app):
            $s = $app['status'];
        ?>
          <tr>
            <td><?= (int)$app['id'] ?></td>
            <td><?= e($app['name']) ?></td>
            <td><?= e($app['city']) ?></td>
            <td><?= e($app['vehicle_type']) ?></td>
            <td><span class="badge <?= e($statusClass[$s] ?? '') ?>"><?= e($statusLabels[$s] ?? $s) ?></span></td>
            <td><?= e(date('d.m.Y H:i', strtotime($app['created_at']))) ?></td>
            <td><a class="btn btn-sm btn-secondary" href="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>">Aç</a></td>
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
