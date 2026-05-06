<?php
declare(strict_types=1);

function supported_locales(): array {
    return ['bs', 'en', 'tr'];
}

function current_locale(): string {
    $q = $_GET['lang'] ?? null;
    if (is_string($q) && in_array($q, supported_locales(), true)) {
        $_SESSION['lang'] = $q;
    }
    $lang = $_SESSION['lang'] ?? 'bs';
    return in_array($lang, supported_locales(), true) ? $lang : 'bs';
}

function t(string $key): string {
    static $dict = [];
    $lang = current_locale();
    if (!isset($dict[$lang])) {
        $dict[$lang] = require __DIR__ . "/i18n/{$lang}.php";
    }
    return $dict[$lang][$key] ?? $key;
}

function render(string $view, array $data): void {
    $GLOBALS['__view_data'] = $data;
    $GLOBALS['__view_name'] = $view;
    require __DIR__ . '/views/layout.php';
}

function render_to_string(string $view, array $data): string {
    $GLOBALS['__view_data'] = $data;
    $GLOBALS['__view_name'] = $view;
    ob_start();
    require __DIR__ . '/views/' . $view . '.php';
    return (string)ob_get_clean();
}