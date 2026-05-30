<?php
declare(strict_types=1);

class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email AND status = 1 LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        unset($user['password']);
        $_SESSION['user'] = $user;
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    public static function enforce(array $roles = []): void
    {
        if (!self::check()) {
            flash('error', 'Please login first.');
            redirect('/login');
        }
        if ($roles !== [] && !in_array(self::user()['role'], $roles, true)) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }
    }
}
