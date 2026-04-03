<section class="event-detail">

    <div class="event-card">

        <h1 class="event-title">
            <?= View::e($event['title']) ?>
        </h1>

        <div class="event-meta">
            <strong>Start:</strong> <?= View::e($event['start_date']) ?><br>
            <strong>End:</strong> <?= View::e($event['end_date']) ?><br>
            <strong>Organizer:</strong> <?= View::e($event['organizer']) ?>
        </div>

        <?php if ($event['hero_image']): ?>
            <img
                src="/data/<?= View::e($event['hero_image']) ?>"
                alt="Event image"
                class="hero-img"
            >
        <?php endif; ?>

        <h2>Description</h2>
        <p class="event-description">
            <?= nl2br(View::e($event['description'])) ?>
        </p>

        <h2>Workshops</h2>

        <?php if (count($workshops) === 0): ?>
            <p class="muted">No workshops are registered for this event.</p>
        <?php else: ?>
            <ul class="workshop-list">
                <?php foreach ($workshops as $w): ?>
                    <li><?= View::e($w['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <hr class="divider">

        <?php if ($user): ?>

            <?php if ($isOwner): ?>
                <a href="<?= BASE_PATH ?>/events/<?= (int)$event['id'] ?>/edit"
                   class="btn btn-warn">
                    Update Event
                </a>

            <?php elseif (!$isRegistered): ?>
                <a href="<?= BASE_PATH ?>/events/<?= (int)$event['id'] ?>/register"
                   class="btn btn-accent">
                    Register for Event
                </a>

            <?php else: ?>
                <form action="<?= BASE_PATH ?>/events/<?= (int)$event['id'] ?>/cancel"
                      method="post"
                      class="inline-form">
                    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                    <button type="submit" class="btn btn-danger">
                        Cancel Registration
                    </button>
                </form>
            <?php endif; ?>

        <?php else: ?>

            <p class="muted">
                Please <a href="<?= BASE_PATH ?>/login" class="link-accent">log in</a> to register.
            </p>

        <?php endif; ?>

    </div>

</section>
