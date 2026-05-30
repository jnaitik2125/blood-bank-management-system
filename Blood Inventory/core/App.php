<?php
declare(strict_types=1);

class App
{
    private array $routes;

    public function __construct()
    {
        $this->routes = require __DIR__ . '/../routes/web.php';
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $base = rtrim((string) config('base_path', ''), '/');
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = $uri === '' ? '/' : $uri;

        foreach ($this->routes as $route) {
            if ($method !== $route['method']) {
                continue;
            }
            $pattern = '#^' . preg_replace('/\{id\}/', '([0-9]+)', $route['path']) . '$#';
            if (preg_match($pattern, $uri, $matches) === 1) {
                array_shift($matches);
                if (!empty($route['roles'])) {
                    Auth::enforce($route['roles']);
                }
                [$controllerName, $action] = $route['handler'];
                $controller = new $controllerName();
                $controller->{$action}(...array_map('intval', $matches));
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
