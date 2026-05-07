<?php
$title      = 'Başvuru Detayı';
$activePage = 'applications';

$app = $application ?? [];
$statusLabels = ['pending' => 'Bekliyor', 'approved' => 'Onaylandı', 'rejected' => 'Reddedildi'];
$statusClass  = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
$currentStatus = (string)($app['status'] ?? 'pending');

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Başvuru #<?= (int)($app['id'] ?? 0) ?></h1>
  <a href="<?= e(BASE_PATH) ?>/admin/applications" class="lang-btn">← Listeye Dön</a>
</div>

<div class="admin-detail-grid" style="margin-bottom:18px">
  <div class="card">
    <div class="card-pad">
      <h2 style="margin-top:0;font-size:1.2rem">Başvuru Bilgileri</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px">
        <div><strong>Ad Soyad:</strong><br><?= e($app['name'] ?? '') ?></div>
        <div><strong>E-posta:</strong><br><a href="mailto:<?= e($app['email'] ?? '') ?>"><?= e($app['email'] ?? '') ?></a></div>
        <div><strong>Telefon:</strong><br><?= e($app['phone'] ?? '') ?></div>
        <div><strong>Şehir:</strong><br><?= e($app['city'] ?? '') ?></div>
        <div><strong>Araç Tipi:</strong><br><?= e($app['vehicle_type'] ?? '') ?></div>
        <div><strong>Davet Kodu:</strong><br><?= e(($app['referral_code'] ?? '') !== '' ? $app['referral_code'] : '—') ?></div>
        <div><strong>Tarih:</strong><br><?= !empty($app['created_at']) ? e(date('d.m.Y H:i', strtotime($app['created_at']))) : '' ?></div>
        <div>
          <strong>Durum:</strong><br>
          <span class="badge <?= e($statusClass[$currentStatus] ?? '') ?>"><?= e($statusLabels[$currentStatus] ?? $currentStatus) ?></span>
        </div>
      </div>

      <div style="margin-top:14px">
        <strong>Mesaj:</strong>
        <div class="card" style="margin-top:6px;box-shadow:none;border:1px solid #e8ecef">
          <div class="card-pad" style="padding:12px 14px;white-space:pre-wrap"><?= e($app['message'] ?? '') ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-pad">
      <h2 style="margin-top:0;font-size:1.2rem">İşlemler</h2>

      <form method="POST" action="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>/status" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px">
        <?= csrf_field() ?>
        <input type="hidden" name="back_to" value="detail">
        <?php if ($currentStatus !== 'approved'): ?>
          <button type="submit" name="status" value="approved" class="btn btn-sm btn-success">Onayla</button>
        <?php endif; ?>
        <?php if ($currentStatus !== 'rejected'): ?>
          <button type="submit" name="status" value="rejected" class="btn btn-sm btn-danger">Reddet</button>
        <?php endif; ?>
        <?php if ($currentStatus !== 'pending'): ?>
          <button type="submit" name="status" value="pending" class="btn btn-sm btn-warning">Beklet</button>
        <?php endif; ?>
      </form>

      <form method="POST" action="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>/delete" onsubmit="return confirm('Bu başvuru silinsin mi?')" style="margin-bottom:14px">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-sm btn-danger">Başvuruyu Sil</button>
      </form>

      <form method="POST" action="<?= e(BASE_PATH) ?>/admin/applications/<?= (int)$app['id'] ?>/note">
        <?= csrf_field() ?>
        <label class="label" for="admin_note">Admin Notu</label>
        <textarea id="admin_note" name="admin_note" class="input" style="min-height:120px;resize:vertical"><?= e($app['admin_note'] ?? '') ?></textarea>
        <button type="submit" class="btn" style="margin-top:10px;background:var(--green);color:#fff;width:auto;padding:0 18px">Notu Kaydet</button>
      </form>
    </div>
  </div>
</div>

<div class="card" style="padding:0;overflow:hidden">
  <div class="card-pad" style="padding:16px 20px;border-bottom:1px solid #ecf0f1">
    <strong>Durum Geçmişi</strong>
  </div>
  <?php if (empty($history)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Durum geçmişi bulunamadı.</p>
  <?php else: ?>
    <div style="overflow:auto">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Tarih</th>
            <th>Önceki Durum</th>
            <th>Yeni Durum</th>
            <th>Admin</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $h): ?>
            <tr>
              <td><?= e(date('d.m.Y H:i', strtotime($h['changed_at']))) ?></td>
              <td><?= e($statusLabels[$h['old_status']] ?? $h['old_status']) ?></td>
              <td><?= e($statusLabels[$h['new_status']] ?? $h['new_status']) ?></td>
              <td><?= e($h['admin_email'] ?: ('#' . (int)$h['changed_by'])) ?></td>
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
