<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sürücü Başvurusu – Rider</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
        }
        header {
            background: #1a1a2e;
            color: #fff;
            text-align: center;
            padding: 1.5rem 1rem;
        }
        header h1 { font-size: 1.5rem; }
        header p  { font-size: .9rem; color: #c8d6e5; margin-top: .3rem; }
        .container { max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,.08);
            padding: 2rem;
        }
        .alert {
            padding: .8rem 1rem;
            border-radius: 7px;
            margin-bottom: 1.2rem;
            font-size: .875rem;
        }
        .alert-danger  { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .4rem; color: #444; }
        input[type=text], input[type=email], input[type=tel], select, textarea {
            width: 100%;
            padding: .65rem .9rem;
            border: 1.5px solid #d0d7df;
            border-radius: 7px;
            font-size: .95rem;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #1a1a2e; }
        textarea { resize: vertical; min-height: 100px; }
        .btn-submit {
            display: block;
            width: 100%;
            padding: .8rem;
            background: #1a1a2e;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1.5rem;
        }
        .btn-submit:hover { opacity: .88; }
        .required { color: #e74c3c; }
    </style>
</head>
<body>

<header>
    <h1>🏍 Rider Sürücü Başvurusu</h1>
    <p>Formu doldurun, ekibimiz sizinle iletişime geçsin.</p>
</header>

<div class="container">
    <div class="card">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= e(BASE_PATH) ?>/apply">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Ad Soyad <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                       value="<?= e($_POST['name'] ?? '') ?>"
                       required placeholder="Adınız Soyadınız">
            </div>

            <div class="form-group">
                <label for="email">E-posta <span class="required">*</span></label>
                <input type="email" id="email" name="email"
                       value="<?= e($_POST['email'] ?? '') ?>"
                       required placeholder="ornek@email.com">
            </div>

            <div class="form-group">
                <label for="phone">Telefon <span class="required">*</span></label>
                <input type="tel" id="phone" name="phone"
                       value="<?= e($_POST['phone'] ?? '') ?>"
                       required placeholder="05XX XXX XX XX">
            </div>

            <?php if (!empty($cities)): ?>
            <div class="form-group">
                <label for="city">Şehir</label>
                <select id="city" name="city">
                    <option value="">Seçiniz...</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= e($c['name']) ?>"
                            <?= ($_POST['city'] ?? '') === $c['name'] ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <div class="form-group">
                <label for="city">Şehir</label>
                <input type="text" id="city" name="city"
                       value="<?= e($_POST['city'] ?? '') ?>"
                       placeholder="Şehrinizi yazın">
            </div>
            <?php endif; ?>

            <?php if (!empty($vehicle_types)): ?>
            <div class="form-group">
                <label for="vehicle_type">Araç Tipi</label>
                <select id="vehicle_type" name="vehicle_type">
                    <option value="">Seçiniz...</option>
                    <?php foreach ($vehicle_types as $v): ?>
                        <option value="<?= e($v['name']) ?>"
                            <?= ($_POST['vehicle_type'] ?? '') === $v['name'] ? 'selected' : '' ?>>
                            <?= e($v['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="message">Notunuz</label>
                <textarea id="message" name="message"
                          placeholder="Eklemek istediğiniz bir şey varsa buraya yazabilirsiniz..."><?= e($_POST['message'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Başvuru Gönder</button>
        </form>
    </div>
</div>

</body>
</html>
