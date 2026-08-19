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

    private static function sameOrigin(): bool
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($host === '') return false;
        foreach (['HTTP_ORIGIN', 'HTTP_REFERER'] as $header) {
            if (!empty($_SERVER[$header])) {
                $originHost = parse_url($_SERVER[$header], PHP_URL_HOST);
                return is_string($originHost) && strcasecmp($originHost, $host) === 0;
            }
        }
        return false;
    }

    public static function requireCsrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $token = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (self::verifyCsrf(is_string($token) ? $token : null)) return;
        if (self::sameOrigin()) return;
        http_response_code(419);
        exit('Invalid security token. Please refresh and try again.');
    }
}
