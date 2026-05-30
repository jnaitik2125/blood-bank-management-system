<?php
declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config/config.php';
    }

    $parts = explode('.', $key);
    $value = $config;
    foreach ($parts as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $default;
        }
        $value = $value[$part];
    }
    return $value;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    $base = rtrim((string) config('base_path', ''), '/');
    header('Location: ' . $base . $path);
    exit;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function is_valid_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_phone(string $phone): bool
{
    if ($phone === '') {
        return false;
    }
    return (bool) preg_match('/^\+?[0-9]{10,15}$/', $phone);
}

function is_valid_date(string $date): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d instanceof DateTime && $d->format('Y-m-d') === $date;
}

function is_valid_blood_group(string $group): bool
{
    return in_array($group, ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'], true);
}

function paginate_links(int $currentPage, int $totalPages, string $basePath, array $query = []): string
{
    if ($totalPages <= 1) {
        return '';
    }
    $base = rtrim((string) config('base_path', ''), '/');
    $html = '<div class="pagination">';
    for ($page = 1; $page <= $totalPages; $page++) {
        $query['page'] = $page;
        $url = $base . $basePath . '?' . http_build_query($query);
        $active = $page === $currentPage ? ' active' : '';
        $html .= '<a class="btn' . $active . '" href="' . e($url) . '">' . e((string) $page) . '</a> ';
    }
    $html .= '</div>';
    return $html;
}
