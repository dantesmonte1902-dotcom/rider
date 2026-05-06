<?php
declare(strict_types=1);

$apps = v('apps', []);
$status = (string)v('status', '');
$q = (string)v('q', '');
?>
<section class="hero">
  <div class="container">
    <div class="card">
      <div class="card-pad">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
          <h1 class="h1" style="margin-bottom:0"><?= htmlspecialchars(t('applications')) ?></h1>
          <a class="lang-btn" href="<?= htmlspecialchars(BASE_PATH) ?>/admin/logout"><?= htmlspecialchars(t('logout')) ?></a>
        </div>

        <form method="get" action="<?= htmlspecialchars(BASE_PATH) ?>/admin/applications" style="margin:14px 0;display:flex;gap:10px;flex-wrap:wrap">
          <input class="input" style="max-width:280px" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="<?= htmlspecialchars(t('search')) ?>">
          <select name="status" style="max-width:220px">
            <option value=""><?= htmlspecialchars(t('all_status')) ?></option>
            <?php foreach (['new','contacted','approved','rejected'] as $s): ?>
              <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
          <button class="lang-btn" type="submit"><?= htmlspecialchars(t('filter')) ?></button>
        </form>

        <div style="overflow:auto">
          <table style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee">ID</th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee"><?= htmlspecialchars(t('full_name')) ?></th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee"><?= htmlspecialchars(t('phone')) ?></th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee"><?= htmlspecialchars(t('city')) ?></th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee"><?= htmlspecialchars(t('vehicle_type')) ?></th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee">Status</th>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #eee">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($apps as $a): ?>
                <tr>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1"><?= (int)$a['id'] ?></td>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($a['full_name']) ?></td>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1">
                    <?= htmlspecialchars($a['phone']) ?><br>
                    <a href="https://wa.me/<?= preg_replace('/\D+/', '', $a['phone']) ?>" target="_blank" rel="noreferrer">WhatsApp</a>
                    |
                    <a href="viber://chat?number=<?= urlencode($a['phone']) ?>">Viber</a>
                  </td>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($a['city']) ?></td>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($a['vehicle_type']) ?></td>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($a['status']) ?></td>
                  <td style="padding:10px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($a['created_at']) ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (!$apps): ?>
                <tr><td colspan="7" style="padding:14px"><?= htmlspecialchars(t('no_results')) ?></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</section>