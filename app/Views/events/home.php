<section class="stack">

    <h1 class="page-title">Newest Events</h1>

    <?php if (count($events) === 0): ?>
        <p>No events yet. Be the first to create one!</p>
    <?php else: ?>

        <div class="stack">

            <?php foreach ($events as $event): ?>

                <article class="card">

                    <h2 class="card-title">
                        <?= View::e($event['title']) ?>
                    </h2>

                    <p class="card-meta">
                        <strong>Start:</strong> <?= View::e($event['start_date']) ?><br>
                        <strong>End:</strong> <?= View::e($event['end_date']) ?><br>
                        <strong>Organizer:</strong>
                        <?= View::e($event['organizer'] ?? 'Unknown') ?>
                    </p>

                    <a class="link-accent"
                       href="<?= BASE_PATH ?>/events/<?= (int)$event['id'] ?>">
                        View Details →
                    </a>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>
