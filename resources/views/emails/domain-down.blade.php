<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; background: #f5f5f5; padding: 20px; }
        .card { background: #fff; border-radius: 8px; padding: 30px; max-width: 500px; margin: 0 auto; border-left: 4px solid #dc3545; }
        h2 { color: #dc3545; margin-top: 0; }
        .url { font-size: 1.1em; font-weight: bold; word-break: break-all; }
        .footer { margin-top: 20px; font-size: 0.85em; color: #888; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🔴 Домен недоступний</h2>
        <p>Автоматична перевірка показала, що наступний домен перестав відповідати:</p>
        <p class="url">{{ $url }}</p>
        <p>Будь ласка, перевірте стан сервера або DNS-налаштування.</p>
        <div class="footer">
            Це автоматичне повідомлення від User Domains Monitor.
        </div>
    </div>
</body>
</html>
