<?php
require_once __DIR__ . "/header.php";
require_once __DIR__ . "/db.php"; // assumes you have a DB connection here

// fetch 3 newest events
$sql = "
    SELECT e.id, e.title, e.start_date, e.end_date, u.username AS organizer
    FROM events e
    LEFT JOIN users u ON e.created_by = u.id
    ORDER BY e.start_date DESC
    LIMIT 3
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<main style="padding: 24px; font-family: Arial, sans-serif;">

    <h1 style="font-size: 32px; color: #1E1E2F;">Newest Events</h1>

    <?php if (count($events) === 0): ?>
        <p>No events yet. Be the first to create one!</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 18px; margin-top: 18px;">
            <?php foreach ($events as $event): ?>
                <div style="border: 1px solid #ccc; padding: 18px; border-radius: 6px;">
                    <h2 style="margin: 0;"><?php echo htmlspecialchars($event['title']); ?></h2>
                    <p style="margin: 6px 0;">
                        <strong>Start:</strong> <?php echo htmlspecialchars($event['start_date']); ?><br>
                        <strong>End:</strong> <?php echo htmlspecialchars($event['end_date']); ?><br>
                        <strong>Organizer:</strong> <?php echo htmlspecialchars($event['organizer'] ?? "Unknown"); ?>
                    </p>

                    <a style="color: #00E5FF; text-decoration: none; font-weight: bold;"
                       href="<?php echo $baseUrl . "/events/index.php/" . urlencode($event['id']); ?>">
                        View Details →
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

</body>
</html>
