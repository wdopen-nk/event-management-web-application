<section class="event-form-page">

    <div class="event-card">

        <h1>Edit Event</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= View::e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="event-form">

            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

            <label>Name</label>
            <input type="text"
                   name="name"
                   required
                   maxlength="64"
                   value="<?= View::e($event['title']) ?>">

            <label>Description</label>
            <textarea name="description"
                      required
                      maxlength="1024"
                      rows="4"><?= View::e($event['description']) ?></textarea>

            <label>Start Date</label>
            <input type="date"
                   name="start_date"
                   required
                   value="<?= View::e($event['start_date']) ?>">

            <label>End Date</label>
            <input type="date"
                   name="end_date"
                   required
                   value="<?= View::e($event['end_date']) ?>">

            <label>Hero Image</label>
            <input type="file" name="hero_image" accept="image/*">

            <?php if ($event['hero_image']): ?>
                <img src="/data/<?= View::e($event['hero_image']) ?>"
                     class="preview-img"
                     alt="Current hero image">
            <?php endif; ?>

            <h3>Workshops</h3>

            <div id="workshops-container">
                <?php foreach ($workshops as $w): ?>
                    <input type="text"
                           name="workshops[]"
                           value="<?= View::e($w['name']) ?>">
                <?php endforeach; ?>

                <input type="text" name="workshops[]" placeholder="Workshop name">
            </div>

            <button type="button" id="add-workshop" class="btn btn-accent">
                + Add Workshop
            </button>

            <button type="submit" name="save" class="btn btn-primary">
                Save Changes
            </button>

        </form>

        <hr>

        <form method="post" onsubmit="return confirm('Are you sure you want to delete this event?');">
            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
            <button type="submit" name="delete" class="btn btn-danger">
                Delete Event
            </button>
        </form>

    </div>

</section>

<script src="<?= BASE_PATH ?>/assets/events-form.js" defer></script>
