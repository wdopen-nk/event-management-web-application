<section class="stack" data-events-page>

    <h1 class="page-title">All Events</h1>

    <?php if (count($events) === 0): ?>

        <p>No events found.</p>

    <?php else: ?>

        <div class="stack" data-list>

            <?php foreach ($events as $event): ?>

                <article class="card event-card" data-event-item>

                    <h2 class="card-title">
                        <?= View::e($event['title']) ?>
                    </h2>

                    <p class="card-meta">
                        <strong>Start:</strong> <?= View::e($event['start_date']) ?><br>
                        <strong>End:</strong> <?= View::e($event['end_date']) ?><br>
                        <strong>Organizer:</strong>
                        <?= View::e($event['organizer']) ?>
                    </p>

                    <a class="link-accent"
                       href="<?= BASE_PATH ?>/events/<?= (int)$event['id'] ?>">
                        View Details →
                    </a>

                </article>

            <?php endforeach; ?>

        </div>

        <div class="pagination" data-pager>
            <span class="page-status" data-status></span>
        </div>

    <?php endif; ?>

</section>

<script src="<?= BASE_PATH ?>/assets/events-list.js" defer></script>
