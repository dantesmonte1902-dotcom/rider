<?php
declare(strict_types=1);

/**
 * Admin Sıfırlama Aracı — HTML Arayüzü
 * ─────────────────────────────────────────────────────────────────────────────
 * GÜVENLİK: Bu dosya yalnızca localhost/127.0.0.1'den erişilebilir.
 * Canlı sunucuda kullanmak zorundasanız APP_LOCAL=1 env değişkenini ayarlayın
 * ve işiniz bittiğinde DOSYAYI SİLİN.
 *
 * XAMPP'da açmak için: http://localhost/rider/reset-admin.php
 */

require __DIR__ . '/app/config.php';
require __DIR__ . '/app/db.php';

// ── Guard: local-only ─────────────────────────────────────────────────────────
if (!IS_LOCAL) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "403 Forbidden — Bu araç yalnızca localhost'tan erişilebilir.\n";
    exit;
}

// ── POST işlemi ───────────────────────────────────────────────────────────────
$message = '';
$msgType = 'info'; // info | success | error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = strtolower(trim((string)($_POST['action'] ?? 'reset')));
    $email   = trim((string)($_POST['email'] ?? ''));
    $newPass = (string)($_POST['pass'] ?? '');

    if ($email === '' || $newPass === '') {
        $message = 'E-posta ve şifre alanları boş bırakılamaz.';
        $msgType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Geçersiz e-posta adresi.';
        $msgType = 'error';
    } elseif (strlen($newPass) < 6) {
        $message = 'Şifre en az 6 karakter olmalıdır.';
        $msgType = 'error';
    } else {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);

        try {
            if ($action === 'create') {
                $stmt = db()->prepare(
                    'INSERT INTO admin_users (email, password_hash) VALUES (:email, :hash)'
                );
                $stmt->execute([':email' => $email, ':hash' => $hash]);
                $message = "✅ Admin oluşturuldu → {$email}";
                $msgType = 'success';
            } else {
                // upsert: varsa güncelle, yoksa ekle
                $stmt = db()->prepare(
                    'INSERT INTO admin_users (email, password_hash) VALUES (:email, :hash)
                     ON DUPLICATE KEY UPDATE password_hash = :hash2'
                );
                $stmt->execute([':email' => $email, ':hash' => $hash, ':hash2' => $hash]);
                $message = "✅ Şifre güncellendi → {$email}  |  Giriş yap: " . BASE_PATH . '/admin/login';
                $msgType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . htmlspecialchars($e->getMessage());
            $msgType = 'error';
        }
    }
}

// ── Mevcut admin listesi ──────────────────────────────────────────────────────
$admins = [];
try {
    $admins = db()->query('SELECT id, email, created_at FROM admin_users ORDER BY id ASC')
                  ->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // tablo yoksa sessizce geç
}

?><!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Sıfırlama — Rider</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:system-ui,sans-serif;background:#f4f6f9;display:flex;justify-content:center;padding:40px 16px;min-height:100vh}
  .card{background:#fff;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,.1);padding:36px 40px;width:100%;max-width:480px;align-self:flex-start}
  h1{font-size:1.4rem;font-weight:700;color:#1a1a2e;margin-bottom:4px}
  .subtitle{font-size:.85rem;color:#888;margin-bottom:28px}
  label{display:block;font-size:.85rem;font-weight:600;color:#444;margin-bottom:6px}
  input[type=email],input[type=password]{width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:8px;font-size:.95rem;outline:none;transition:border-color .2s}
  input:focus{border-color:#4f8ef7}
  .field{margin-bottom:18px}
  .row{display:flex;gap:10px;margin-bottom:24px}
  .btn{flex:1;padding:11px;border:none;border-radius:8px;font-size:.95rem;font-weight:600;cursor:pointer;transition:opacity .15s}
  .btn-primary{background:#4f8ef7;color:#fff}
  .btn-create{background:#27ae60;color:#fff}
  .btn:hover{opacity:.88}
  .msg{padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:.9rem;line-height:1.4}
  .msg.success{background:#e6f9ed;color:#1e7e34;border:1px solid #a3d9b1}
  .msg.error{background:#fdecea;color:#c0392b;border:1px solid #f5b7b1}
  .msg.info{background:#eaf3ff;color:#2563eb;border:1px solid #bfdbfe}
  .divider{border:none;border-top:1px solid #eee;margin:28px 0}
  table{width:100%;border-collapse:collapse;font-size:.85rem}
  th{text-align:left;padding:8px 10px;background:#f8f9fa;color:#555;font-weight:600;border-bottom:2px solid #eee}
  td{padding:8px 10px;border-bottom:1px solid #f0f0f0;color:#333}
  tr:last-child td{border-bottom:none}
  .empty{color:#aaa;font-size:.85rem;padding:12px 0}
  .warning{font-size:.78rem;color:#e67e22;margin-top:20px;line-height:1.5}
</style>
</head>
<body>
<div class="card">
  <h1>🔐 Admin Sıfırlama</h1>
  <p class="subtitle">localhost — yalnızca yerel erişim</p>

  <?php if ($message !== ''): ?>
  <div class="msg <?= htmlspecialchars($msgType) ?>"><?= $message ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="field">
      <label for="email">E-posta</label>
      <input type="email" id="email" name="email" placeholder="admin@example.com"
             value="<?= htmlspecialchars((string)($_POST['email'] ?? '')) ?>" required>
    </div>
    <div class="field">
      <label for="pass">Yeni Şifre</label>
      <input type="password" id="pass" name="pass" placeholder="En az 6 karakter" required>
    </div>
    <div class="row">
      <button type="submit" name="action" value="reset" class="btn btn-primary">
        🔄 Şifreyi Güncelle / Upsert
      </button>
      <button type="submit" name="action" value="create" class="btn btn-create">
        ➕ Yeni Ekle
      </button>
    </div>
  </form>

  <hr class="divider">

  <strong style="font-size:.85rem;color:#555">Mevcut Admin Kullanıcıları</strong>
  <?php if (empty($admins)): ?>
    <p class="empty">Henüz admin kaydı yok.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>E-posta</th><th>Oluşturulma</th></tr></thead>
      <tbody>
        <?php foreach ($admins as $a): ?>
        <tr>
          <td><?= (int)$a['id'] ?></td>
          <td><?= htmlspecialchars($a['email']) ?></td>
          <td><?= htmlspecialchars($a['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <p class="warning">⚠️ Canlı sunucuda bu dosyayı kullandıktan sonra <strong>silin</strong>.</p>
</div>
</body>
</html>
