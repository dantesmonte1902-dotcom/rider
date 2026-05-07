<?php
$title      = 'Admin Kullanıcıları';
$activePage = 'users';

ob_start();
?>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px">
  <h1 class="h1" style="margin:0">Admin Kullanıcıları</h1>
</div>

<?php if (!empty($db_error)): ?>
  <div class="alert-danger">Veritabanı hatası: <?= e($db_error) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:18px">
  <div class="card-pad" style="padding:16px 20px">
    <form method="POST" action="<?= e(BASE_PATH) ?>/admin/users" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <?= csrf_field() ?>
      <div>
        <label class="label" for="admin_email" style="font-size:.82rem">E-posta</label>
        <input class="input" type="email" id="admin_email" name="email" required placeholder="admin@example.com" style="width:220px">
      </div>
      <div>
        <label class="label" for="admin_password" style="font-size:.82rem">Şifre</label>
        <input class="input" type="password" id="admin_password" name="password" required minlength="8" style="width:180px">
      </div>
      <button class="btn" type="submit" style="background:var(--green);color:#fff;width:auto;padding:0 20px">+ Admin Ekle</button>
    </form>
  </div>
</div>

<div class="card" style="padding:0;overflow:hidden">
  <?php if (empty($admins)): ?>
    <p style="padding:2rem;text-align:center;color:var(--muted)">Admin bulunamadı.</p>
  <?php else: ?>
    <div style="overflow:auto">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>E-posta</th>
            <th>Oluşturulma</th>
            <th>Sil</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($admins as $admin): ?>
          <tr>
            <td><?= (int)$admin['id'] ?></td>
            <td><?= e($admin['email']) ?><?= (int)$admin['id'] === (int)($_SESSION['admin_id'] ?? 0) ? ' <span style="color:var(--muted)">(Siz)</span>' : '' ?></td>
            <td><?= e(date('d.m.Y H:i', strtotime($admin['created_at']))) ?></td>
            <td>
              <?php if ((int)$admin['id'] !== (int)($_SESSION['admin_id'] ?? 0)): ?>
                <form method="POST" action="<?= e(BASE_PATH) ?>/admin/users/<?= (int)$admin['id'] ?>/delete" onsubmit="return confirm('Bu admin silinsin mi?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                </form>
              <?php else: ?>
                <span style="opacity:.5">—</span>
              <?php endif; ?>
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
