<?php
declare(strict_types=1);

/**
 * DB
 *
 * Centralized database access layer.
 * Implements a lazy-loaded singleton PDO connection.
 *
 * This class ensures:
 *  - Only one PDO connection exists per request
 *  - Credentials are not scattered across the codebase
 *  - All database access is done via prepared statements (PDO)
 */
final class DB
{
    private static ?PDO $pdo = null;

    /**
     * Returns a PDO connection.
     * Creates it on first call (lazy initialization).
     */
    public static function pdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dsn = "mysql:host=localhost;dbname=stud_my_user;charset=utf8mb4";
        $user = "my_user";
        $password = "my_password";

        self::$pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        return self::$pdo;
    }

    // Prevent instantiation
    private function __construct() {}
}
