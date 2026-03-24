<?php

final class UserModel
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            SELECT id, username, email
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
    
    public static function emailExists(string $email): bool
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            SELECT 1 FROM users WHERE email = ? LIMIT 1
        ");
        $stmt->execute([$email]);

        return (bool) $stmt->fetchColumn();
    }

    public static function create(string $username, string $email): void
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, created_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->execute([$username, $email]);
    }

    public static function updateName(int $userId, string $name): void
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            UPDATE users SET username = ? WHERE id = ?
        ");
        $stmt->execute([$name, $userId]);
    }

    public static function deleteAccount(int $userId): void
    {
        $pdo = DB::pdo();
        $pdo->beginTransaction();

        // delete workshop registrations
        $pdo->prepare("
            DELETE rw FROM registration_workshops rw
            INNER JOIN registrations r ON r.id = rw.registration_id
            WHERE r.user_id = ?
        ")->execute([$userId]);

        // delete registrations
        $pdo->prepare("
            DELETE FROM registrations WHERE user_id = ?
        ")->execute([$userId]);

        // delete workshops of user's events
        $pdo->prepare("
            DELETE w FROM workshops w
            INNER JOIN events e ON e.id = w.event_id
            WHERE e.created_by = ?
        ")->execute([$userId]);

        // delete events
        $pdo->prepare("
            DELETE FROM events WHERE created_by = ?
        ")->execute([$userId]);

        // delete user
        $pdo->prepare("
            DELETE FROM users WHERE id = ?
        ")->execute([$userId]);

        $pdo->commit();
    }
}