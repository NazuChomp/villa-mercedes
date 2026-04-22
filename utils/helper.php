<?php

function url(string $path = ''): string
{
    $base = '/villa-mercedes';
    $path = ltrim($path, '/');

    return $base . '/' . $path;
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function flash(string $type, string $text): void 
{
    $_SESSION['flash'] = [
        'type' => $type,
        'text' => $text
    ];
}
