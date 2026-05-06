<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Başvuru Alındı – Rider</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,.1);
            padding: 3rem 2rem;
            max-width: 480px;
            text-align: center;
        }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
        h1 { font-size: 1.6rem; margin-bottom: .6rem; color: #27ae60; }
        p  { color: #666; line-height: 1.6; margin-bottom: 1.5rem; }
        a {
            display: inline-block;
            padding: .65rem 1.5rem;
            background: #1a1a2e;
            color: #fff;
            border-radius: 7px;
            text-decoration: none;
            font-weight: 600;
            transition: opacity .15s;
        }
        a:hover { opacity: .85; }
    </style>
</head>
<body>

<div class="card">
    <div class="icon">✅</div>
    <h1>Başvurunuz Alındı!</h1>
    <p>Ekibimiz en kısa sürede sizinle iletişime geçecektir. Teşekkür ederiz!</p>
    <a href="<?= e(BASE_PATH) ?>/apply">Yeni Başvuru Yap</a>
</div>

</body>
</html>
