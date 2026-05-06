<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? 'Hata') ?></title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 4rem 1rem; color: #333; }
        h1 { font-size: 2rem; margin-bottom: 1rem; }
        p  { color: #666; }
        a  { color: #1a1a2e; }
    </style>
</head>
<body>
    <h1>⚠️ <?= e($title ?? 'Hata') ?></h1>
    <p><?= e($message ?? 'Beklenmeyen bir hata oluştu.') ?></p>
    <p><a href="<?= e(BASE_PATH) ?>/">Ana sayfaya dön</a></p>
</body>
</html>
