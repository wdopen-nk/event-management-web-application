<?php
declare(strict_types=1);

/**
 * RegistrationModel
 *
 * Handles event registrations and workshop selections.
 */
final class RegistrationModel
{
    public static function isRegistered(int $userId, int $eventId): bool
    {
        $stmt = DB::pdo()->prepare("
            SELECT 1 FROM registrations
            WHERE user_id = ? AND event_id = ?
        ");
        $stmt->execute([$userId, $eventId]);

        return (bool)$stmt->fetchColumn();
    }

    public static function selectedWorkshopIds(int $userId, int $eventId): array
    {
        $stmt = DB::pdo()->prepare("
            SELECT rw.workshop_id
            FROM registration_workshops rw
            JOIN registrations r ON r.id = rw.registration_id
            WHERE r.user_id = ? AND r.event_id = ?
        ");
        $stmt->execute([$userId, $eventId]);

        return array_column($stmt->fetchAll(), 'workshop_id');
    }

    /**
     * Creates or updates registration and workshop selection.
     */
    public static function save(int $userId, int $eventId, array $workshopIds): void
    {
        $pdo = DB::pdo();
        $pdo->beginTransaction();

        // create or fetch registration
        $stmt = $pdo->prepare("
            SELECT id FROM registrations
            WHERE user_id = ? AND event_id = ?
        ");
        $stmt->execute([$userId, $eventId]);
        $regId = $stmt->fetchColumn();

        if (!$regId) {
            $pdo->prepare("
                INSERT INTO registrations (user_id, event_id, registered_at)
                VALUES (?, ?, NOW())
            ")->execute([$userId, $eventId]);

            $regId = $pdo->lastInsertId();
        }

        // clear old selections
        $pdo->prepare("
            DELETE FROM registration_workshops
            WHERE registration_id = ?
        ")->execute([$regId]);

        // insert new selections ONLY
        foreach ($workshopIds as $wsId) {
            $pdo->prepare("
                INSERT INTO registration_workshops (registration_id, workshop_id)
                VALUES (?, ?)
            ")->execute([$regId, (int)$wsId]);
        }

        $pdo->commit();
    }

    public static function cancel(int $userId, int $eventId): void
    {
        $pdo = DB::pdo();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            SELECT id FROM registrations
            WHERE user_id = ? AND event_id = ?
        ");
        $stmt->execute([$userId, $eventId]);
        $regId = $stmt->fetchColumn();

        if ($regId) {
            $pdo->prepare("
                DELETE FROM registration_workshops WHERE registration_id = ?
            ")->execute([$regId]);

            $pdo->prepare("
                DELETE FROM registrations WHERE id = ?
            ")->execute([$regId]);
        }

        $pdo->commit();
    }

    public static function eventsForUser(int $userId): array
    {
        $stmt = DB::pdo()->prepare("
            SELECT e.id, e.title, e.description, e.start_date, e.end_date, e.hero_image
            FROM events e
            JOIN registrations r ON r.event_id = e.id
            WHERE r.user_id = ?
            ORDER BY e.start_date ASC
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }
}
