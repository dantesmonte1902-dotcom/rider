<?php
$title      = 'Başvurular';
$activePage = 'applications';

$statusLabels = ['pending' => 'Bekliyor', 'approved' => 'Onaylandı', 'rejected' => 'Reddedildi'];
$statusClass  = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];

$queryParams = [
    'status'    => $filter_status,
    'search'    => $filter_search,
    'vehicle'   => $filter_vehicle ?? '',
    'period'    => $filter_period ?? '',
    'date_from' => $filter_from ?? '',
    'date_to'   => $filter_to ?? '',
    'sort'      => $sort ?? 'desc',
];

$exportParams = http_build_query($queryParams);

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Sürücü Başvuruları</h1>
  <a href="<?= e(BASE_PATH) ?>/admin/applications/export?<?= e($exportParams) ?>"
     class="lang-btn" style="white-space:nowrap">📥 CSV İndir</a>
</div>

<?php if (!empty($db_error)): ?>
  <div class="alert-danger">Veritabanı hatası: <?= e($db_error) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:16px 20px">
    <form method="GET" action="<?= e(BASE_PATH) ?>/admin/applications"
          style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <div>
        <label class="label" for="search" style="font-size:.82rem">Ara (ad/e-posta/şehir/telefon)</label>
        <input class="input" type="text" id="search" name="search"
               value="<?= e($filter_search) ?>"
               placeholder="Ara..." style="width:220px">
      </div>
      <div>
        <label class="label" for="status" style="font-size:.82rem">Durum</label>
        <select id="status" name="status" style="width:150px">
          <option value="">Tümü</option>
          <option value="pending"  <?= $filter_status === 'pending'  ? 'selected' : '' ?>>Bekliyor</option>
          <option value="approved" <?= $filter_status === 'approved' ? 'selected' : '' ?>>Onaylandı</option>
          <option value="rejected" <?= $filter_status === 'rejected' ? 'selected' : '' ?>>Reddedildi</option>
        </select>
      </div>
      <div>
        <label class="label" for="vehicle" style="font-size:.82rem">Araç</label>
        <select id="vehicle" name="vehicle" style="width:150px">
          <option value="">Tüm Araçlar</option>
          <?php foreach (($vehicle_types ?? []) as $vt): ?>
            <option value="<?= e($vt) ?>" <?= ($filter_vehicle ?? '') === $vt ? 'selected' : '' ?>>
              <?= e($vt) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="label" for="period" style="font-size:.82rem">Tarih</label>
        <select id="period" name="period" style="width:140px">
          <option value="">Tümü</option>
          <option value="today" <?= ($filter_period ?? '') === 'today' ? 'selected' : '' ?>>Bugün</option>
          <option value="week" <?= ($filter_period ?? '') === 'week' ? 'selected' : '' ?>>Son 7 gün</option>
          <option value="month" <?= ($filter_period ?? '') === 'month' ? 'selected' : '' ?>>Son 30 gün</option>
          <option value="custom" <?= ($filter_period ?? '') === 'custom' ? 'selected' : '' ?>>Özel aralık</option>
        </select>
      </div>
      <div>
        <label class="label" for="date_from" style="font-size:.82rem">Başlangıç</label>
        <input class="input" type="date" id="date_from" name="date_from" value="<?= e($filter_from ?? '') ?>" style="width:160px">
      </div>
      <div>
        <label class="label" for="date_to" style="font-size:.82rem">Bitiş</label>
        <input class="input" type="date" id="date_to" name="date_to" value="<?= e($filter_to ?? '') ?>" style="width:160px">
      </div>
      <div>
        <label class="label" for="sort" style="font-size:.82rem">Sıralama</label>
        <select id="sort" name="sort" style="width:130px">
          <option value="desc" <?= ($sort ?? 'desc') === 'desc' ? 'selected' : '' ?>>Yeni → Eski</option>
          <option value="asc" <?= ($sort ?? 'desc') === 'asc' ? 'selected' : '' ?>>Eski → Yeni</option>
        </select>
      </div>
      <button class="lang-btn" type="submit">Filtrele</button>
      <a href="<?= e(BASE_PATH) ?>/admin/applications" class="lang-btn">Temizle</a>
    </form>
  </div>
</div>

<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:14px 20px;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
    <strong style="font-size:.9rem">Toplu işlem:</strong>
    <button type="button" class="btn btn-sm btn-success" onclick="submitBulk('approved')">Seçili Onayla</button>
    <button type="button" class="btn btn-sm btn-danger" onclick="submitBulk('rejected')">Seçili Reddet</button>
    <button type="button" class="btn btn-sm btn-warning" onclick="submitBulk('pending')">Seçili Beklet</button>
    <button type="button" class="btn btn-sm btn-secondary" onclick="submitBulk('delete')">Seçili Sil</button>
    <span style="margin-left:auto;color:var(--muted);font-size:.85rem">
      Toplam: <?= (int)$total_rows ?> kayıt
    </span>
  </div>
</div>

<div class="card" style="padding:0;overflow:hidden">
  <?php if (empty($applications)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Başvuru bulunamadı.</p>
  <?php else: ?>
  <div style="overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th><input type="checkbox" id="select_all"></th>
          <th>#</th>
          <th>Ad Soyad</th>
          <th>İletişim</th>
          <th>Şehir / Araç</th>
          <th>Davet</th>
          <th>Tarih</th>
          <th>Durum</th>
          <th>İşlem</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($applications as $app): ?>
        <?php
          $s = $app['status'];
          $messagePreview = (string)($app['message'] ?? '');
          if (function_exists('mb_substr')) {
              $messagePreview = mb_substr($messagePreview, 0, 42);
          } else {
              $messagePreview = substr($messagePreview, 0, 42);
          }
          if (strlen((string)($app['message'] ?? '')) > 42) {
              $messagePreview .= '...';
          }
        ?>
        <tr>
          <td><input type="checkbox" class="row-select" value="<?= (int)$app['id'] ?>"></td>
          <td><?= (int)$app['id'] ?></td>
          <td>
            <strong><?= e($app['name']) ?></strong><br>
            <span style="font-size:.8rem;color:var(--muted)"><?= e($messagePreview) ?></span>
          </td>
          <td>
            <a href="mailto:<?= e($app['email']) ?>" style="font-size:.84rem"><?= e($app['email']) ?></a><br>
            <span style="font-size:.84rem"><?= e($app['phone']) ?></span>
          </td>
          <td>
            <span style="font-size:.84rem"><?= e($app['city']) ?></span><br>
            <span style="font-size:.84rem;color:var(--muted)"><?= e($app['vehicle_type']) ?></span>
          </td>
          <td><?= e($app['referral_code'] ?: '—') ?></td>
          <td><?= e(date('d.m.Y H:i', strtotime($app['created_at']))) ?></td>
          <td><span class="badge <?= e($statusClass[$s] ?? '') ?>"><?= e($statusLabels[$s] ?? $s) ?></span></td>
          <td>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
              <a href="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>" class="btn btn-sm btn-secondary">Detay</a>
              <form method="POST" action="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>/status" style="display:inline">
                <?= csrf_field() ?>
                <?php if ($s !== 'approved'): ?>
                  <button type="submit" name="status" value="approved" class="btn btn-sm btn-success">Onayla</button>
                <?php endif; ?>
                <?php if ($s !== 'rejected'): ?>
                  <button type="submit" name="status" value="rejected" class="btn btn-sm btn-danger">Reddet</button>
                <?php endif; ?>
                <?php if ($s !== 'pending'): ?>
                  <button type="submit" name="status" value="pending" class="btn btn-sm btn-warning">Beklet</button>
                <?php endif; ?>
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
$baseForPages = $queryParams;
unset($baseForPages['page']);
?>
<?php if (($total_pages ?? 1) > 1): ?>
  <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <?php for ($p = 1; $p <= (int)$total_pages; $p++): ?>
      <?php $q = http_build_query(array_merge($baseForPages, ['page' => $p])); ?>
      <a class="lang-btn <?= (int)$current_page === $p ? 'active' : '' ?>" href="<?= e(BASE_PATH) ?>/admin/applications?<?= e($q) ?>">
        <?= $p ?>
      </a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<form id="bulkForm" method="POST" action="<?= e(BASE_PATH) ?>/admin/applications/bulk" style="display:none">
  <?= csrf_field() ?>
  <input type="hidden" name="bulk_action" id="bulk_action" value="">
  <div id="bulk_ids"></div>
</form>

<script>
(function () {
  const all = document.getElementById('select_all');
  const rowChecks = () => Array.from(document.querySelectorAll('.row-select'));

  if (all) {
    all.addEventListener('change', function () {
      rowChecks().forEach(cb => { cb.checked = all.checked; });
    });
  }

  window.submitBulk = function (action) {
    const selected = rowChecks().filter(cb => cb.checked).map(cb => cb.value);
    if (!selected.length) {
      alert('Lütfen en az bir başvuru seçin.');
      return;
    }
    if (action === 'delete' && !confirm('Seçili başvurular silinsin mi?')) {
      return;
    }

    const bulkAction = document.getElementById('bulk_action');
    const idsWrap = document.getElementById('bulk_ids');
    const form = document.getElementById('bulkForm');

    bulkAction.value = action;
    idsWrap.innerHTML = '';

    selected.forEach(function (id) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'ids[]';
      input.value = id;
      idsWrap.appendChild(input);
    });

    form.submit();
  }
})();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
