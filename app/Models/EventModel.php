<?php
declare(strict_types=1);

/**
 * EventModel
 *
 * Encapsulates all database access related to events.
 */
final class EventModel
{
    /**
     * Returns newest events limited by count.
     */
    public static function newest(int $limit): array
    {
        $stmt = DB::pdo()->prepare("
            SELECT e.id, e.title, e.start_date, e.end_date, u.username AS organizer
            FROM events e
            LEFT JOIN users u ON e.created_by = u.id
            ORDER BY e.start_date DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /* =========================
       BASIC EVENT FETCHING
       ========================= */

    /**
     * Returns all events with organizer name (for /events list).
     */
    public static function allWithOrganizer(): array
    {
        $stmt = DB::pdo()->query("
            SELECT e.id, e.title, e.start_date, e.end_date,
                   u.username AS organizer
            FROM events e
            LEFT JOIN users u ON u.id = e.created_by
            ORDER BY e.start_date DESC
        ");

        return $stmt->fetchAll();
    }

    /**
     * Returns one event with organizer info (for detail page).
     */
    public static function findWithOrganizer(int $id): ?array
    {
        $stmt = DB::pdo()->prepare("
            SELECT e.*, u.username AS organizer
            FROM events e
            LEFT JOIN users u ON u.id = e.created_by
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);

        $event = $stmt->fetch();
        return $event ?: null;
    }

    /**
     * Returns raw event row (no joins).
     */
    public static function findById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare("
            SELECT * FROM events WHERE id = ?
        ");
        $stmt->execute([$id]);

        $event = $stmt->fetch();
        return $event ?: null;
    }

    /* =========================
       WORKSHOPS
       ========================= */

    /**
     * Returns all workshops for an event.
     */
    public static function workshopsForEvent(int $eventId): array
    {
        $stmt = DB::pdo()->prepare("
            SELECT id, name
            FROM workshops
            WHERE event_id = ?
            ORDER BY id ASC
        ");
        $stmt->execute([$eventId]);

        return $stmt->fetchAll();
    }

    /**
     * Adds a workshop to an event.
     */
    public static function addWorkshop(int $eventId, string $name): void
    {
        $stmt = DB::pdo()->prepare("
            INSERT INTO workshops (event_id, name)
            VALUES (?, ?)
        ");
        $stmt->execute([$eventId, $name]);
    }

    /* =========================
       CREATE EVENT
       ========================= */

    /**
     * Creates a new event and its workshops.
     */
    public static function create(
        int $userId,
        string $title,
        string $description,
        string $start,
        string $end,
        ?string $heroImage,
        array $workshops
    ): int {
        $pdo = DB::pdo();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO events (title, description, start_date, end_date, created_by, hero_image)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $title,
            $description,
            $start,
            $end,
            $userId,
            $heroImage
        ]);

        $eventId = (int)$pdo->lastInsertId();

        foreach ($workshops as $w) {
            $w = trim($w);
            if ($w !== '') {
                self::addWorkshop($eventId, $w);
            }
        }

        $pdo->commit();
        return $eventId;
    }

    /* =========================
       UPDATE EVENT (CRITICAL)
       ========================= */

    /**
     * Updates event + workshops safely.
     *
     * IMPORTANT:
     * - Registrations stay intact
     * - Workshop selections are cleared
     * - Users are NOT auto-registered to new workshops
     */
    public static function updateFromRequest(int $eventId, Request $req): void
    {
        $pdo = DB::pdo();
        $pdo->beginTransaction();

        // Update event fields
        $stmt = $pdo->prepare("
            UPDATE events
            SET title = ?, description = ?, start_date = ?, end_date = ?, hero_image = ?
            WHERE id = ?
        ");

        $heroImage = self::handleHeroImageUpload($req);

        $stmt->execute([
            trim($req->post('name')),
            trim($req->post('description')),
            $req->post('start_date'),
            $req->post('end_date'),
            $heroImage,
            $eventId
        ]);

        // Remove workshop selections ONLY
        $pdo->prepare("
            DELETE rw FROM registration_workshops rw
            JOIN registrations r ON r.id = rw.registration_id
            WHERE r.event_id = ?
        ")->execute([$eventId]);

        // Remove old workshops
        $pdo->prepare("
            DELETE FROM workshops WHERE event_id = ?
        ")->execute([$eventId]);

        // Reinsert workshops
        foreach ($req->post('workshops', []) as $name) {
            $name = trim($name);
            if ($name !== '') {
                self::addWorkshop($eventId, $name);
            }
        }

        $pdo->commit();
    }

    /* =========================
       DELETE EVENT
       ========================= */

    /**
     * Deletes an event and all dependent data.
     */
    public static function delete(int $eventId): void
    {
        $pdo = DB::pdo();
        $pdo->beginTransaction();

        $pdo->prepare("
            DELETE rw FROM registration_workshops rw
            JOIN registrations r ON r.id = rw.registration_id
            WHERE r.event_id = ?
        ")->execute([$eventId]);

        $pdo->prepare("
            DELETE FROM registrations WHERE event_id = ?
        ")->execute([$eventId]);

        $pdo->prepare("
            DELETE FROM workshops WHERE event_id = ?
        ")->execute([$eventId]);

        $pdo->prepare("
            DELETE FROM events WHERE id = ?
        ")->execute([$eventId]);

        $pdo->commit();
    }

    /* =========================
       FILE UPLOAD
       ========================= */

    /**
     * Handles hero image upload (if any).
     */
    private static function handleHeroImageUpload(Request $req): ?string
    {
        if (!isset($_FILES['hero_image']) ||
            $_FILES['hero_image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmp = $_FILES['hero_image']['tmp_name'];
        $orig = basename($_FILES['hero_image']['name']);

        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $orig);
        move_uploaded_file($tmp, __DIR__ . '/../../public/data/' . $filename);

        return $filename;
    }

    public static function findRegisteredByUser(int $userId): array
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            SELECT DISTINCT
                e.id,
                e.title,
                e.description,
                e.start_date,
                e.end_date,
                e.hero_image
            FROM events e
            INNER JOIN registrations r ON r.event_id = e.id
            WHERE r.user_id = ?
            ORDER BY e.start_date ASC
        ");
        
        $stmt->execute([$userId]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // attach workshops per event
        foreach ($events as &$event) {
            $event['workshops'] = self::workshopsForUserAndEvent($userId, $event['id']);
        }

        return $events;
    }

    public static function workshopsForUserAndEvent(int $userId, int $eventId): array
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            SELECT w.name
            FROM workshops w
            INNER JOIN registration_workshops rw ON rw.workshop_id = w.id
            INNER JOIN registrations r ON r.id = rw.registration_id
            WHERE r.user_id = ? AND r.event_id = ?
            ORDER BY w.name
        ");
        $stmt->execute([$userId, $eventId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}