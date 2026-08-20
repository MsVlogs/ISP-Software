<?php

final class Security
{
    public static function bootstrap(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_set_cookie_params([
                'httponly' => true,
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax',
            ]);
            session_start();
        }
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
    }

    public static function csrfToken(): string
    {
        self::bootstrap();
        return $_SESSION['_csrf'];
    }

    public static function verifyCsrf(?string $token): bool
    {
        self::bootstrap();
        return is_string($token) && hash_equals($_SESSION['_csrf'], $token);
    }

    public static function requireCsrf(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        $token = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (!self::verifyCsrf(is_string($token) ? $token : null)) {
            http_response_code(419);
            header('Content-Type: text/plain; charset=UTF-8');
            exit('Invalid security token. Please refresh and try again.');
        }
    }
}
