<?php
declare(strict_types=1);

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $currentUser = Auth::user();
        $appName = config('app_name');
        require __DIR__ . '/../app/views/layouts/header.php';
        require __DIR__ . '/../app/views/' . $view . '.php';
        require __DIR__ . '/../app/views/layouts/footer.php';
        unset($_SESSION['_old']);
    }

    protected function request(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function storeOldInput(): void
    {
        $_SESSION['_old'] = $_POST;
    }

    protected function requireCsrf(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            exit;
        }
    }

    protected function isAjax(): bool
    {
        return (strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest')
            || (($this->request('_ajax') ?? '') === '1');
    }

    protected function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function renderPartial(string $view, array $data = []): string
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../app/views/' . $view . '.php';
        return (string) ob_get_clean();
    }
}
