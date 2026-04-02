<section class="event-form-page">

    <div class="event-card">

        <h1>Create a New Event</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= View::e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="event-form">

            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

            <label>Name</label>
            <input type="text" name="name" required maxlength="64">

            <label>Description</label>
            <textarea name="description" required maxlength="1024" rows="4"></textarea>

            <label>Start Date</label>
            <input type="date" name="start_date" required>

            <label>End Date</label>
            <input type="date" name="end_date" required>

            <label>Hero Image</label>
            <input type="file" name="hero_image" accept="image/*">

            <h3>Workshops</h3>

            <div id="workshops-container">
                <input type="text" name="workshops[]" placeholder="Workshop name">
            </div>

            <button type="button" id="add-workshop" class="btn btn-accent">
                + Add Workshop
            </button>

            <button type="submit" class="btn btn-primary">
                Create Event
            </button>

        </form>

    </div>

</section>

<script src="<?= BASE_PATH ?>/assets/events-form.js" defer></script>
