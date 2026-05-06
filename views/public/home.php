<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa – Rider</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero { padding: 2rem; }
        .hero h1 { font-size: 3rem; margin-bottom: 1rem; }
        .hero p  { font-size: 1.1rem; color: #c8d6e5; margin-bottom: 2rem; max-width: 480px; }
        .hero a {
            display: inline-block;
            padding: .85rem 2rem;
            background: #fff;
            color: #1a1a2e;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: opacity .15s;
            margin: .4rem;
        }
        .hero a:hover { opacity: .9; }
        .hero a.outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,.5);
        }
    </style>
</head>
<body>

<div class="hero">
    <div style="font-size:4rem;margin-bottom:1rem;">🏍</div>
    <h1>Rider</h1>
    <p>Hızlı, güvenli ve konforlu ulaşım. Sürücü olmak için başvurun.</p>
    <a href="<?= e(BASE_PATH) ?>/apply">Sürücü Başvurusu</a>
    <a href="<?= e(BASE_PATH) ?>/admin/login" class="outline">Admin Girişi</a>
</div>

</body>
</html>
