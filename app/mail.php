<?php
declare(strict_types=1);

function send_mail(string $to, string $subject, string $html): void {
    if (MAIL_TRANSPORT === 'log') {
        $dir = __DIR__ . '/../storage/mail_logs';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $file = $dir . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $to) . '.html';
        file_put_contents($file, "TO: {$to}\nSUBJECT: {$subject}\n\n{$html}");
        return;
    }

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>';

    @mail($to, $subject, $html, implode("\r\n", $headers));
}