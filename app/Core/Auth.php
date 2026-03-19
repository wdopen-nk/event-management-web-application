<?php
declare(strict_types=1);

/**
 * Auth
 *
 * Helper for authentication and session-based user access.
 */
final class Auth
{
    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::user()) {
            header('Location: /login');
            exit;
        }
    }

    public static function refreshUserName(string $name): void
    {
        if (isset($_SESSION['user'])) {
            $_SESSION['user']['username'] = $name;
        }
    }

}
