<section class="event-form-page">

    <div class="event-card">

        <h1>Register for Event</h1>
        <h2 class="muted"><?= View::e($event['title']) ?></h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= View::e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="event-form">

            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

            <?php if (count($workshops) > 0): ?>

                <p>Select workshops you want to attend:</p>

                <?php foreach ($workshops as $w): ?>
                    <label class="checkbox-row">
                        <input type="checkbox"
                               name="workshops[]"
                               value="<?= (int)$w['id'] ?>"
                               <?= in_array($w['id'], $selected) ? 'checked' : '' ?>>
                        <?= View::e($w['name']) ?>
                    </label>
                <?php endforeach; ?>

            <?php else: ?>
                <p class="muted">This event has no workshops.</p>
            <?php endif; ?>

            <button type="submit" class="btn btn-accent">
                <?= $isRegistered ? 'Update Registration' : 'Register for Event' ?>
            </button>

        </form>

    </div>

</section>
