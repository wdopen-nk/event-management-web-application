<section class="page">

    <h1>My Registered Events</h1>

    <?php if (empty($events)): ?>

        <p>You are not registered for any events.</p>

    <?php else: ?>

        <?php foreach ($events as $event): ?>

            <article class="event-card">

                <h2><?= View::e($event['title']) ?></h2>

                <?php if (!empty($event['hero_image'])): ?>
                    <img
                        src="/data/<?= View::e($event['hero_image']) ?>"
                        alt="Event image"
                        class="event-image"
                    >
                <?php endif; ?>

                <p><?= nl2br(View::e($event['description'])) ?></p>

                <p class="event-dates">
                    <strong>Start:</strong> <?= View::e($event['start_date']) ?><br>
                    <strong>End:</strong> <?= View::e($event['end_date']) ?>
                </p>

                <h3>Workshops</h3>

                <?php if (empty($event['workshops'])): ?>
                    <p>No workshop registrations.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($event['workshops'] as $ws): ?>
                            <li><?= View::e($ws['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</section>
