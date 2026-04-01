<section class="auth-page">

    <div class="auth-card">

        <h1>Register</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= View::e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="auth-form">

            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

            <label for="full_name">Full name</label>
            <input
                id="full_name"
                type="text"
                name="full_name"
                required
                autocomplete="name"
            >

            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                required
                autocomplete="email"
            >

            <button type="submit" class="btn btn-accent">
                Register
            </button>

        </form>

        <p class="auth-footer">
            Already have an account?
            <a href="<?= BASE_PATH ?>/login">Login here</a>
        </p>

    </div>

</section>
