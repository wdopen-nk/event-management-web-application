<section class="auth-page">

    <div class="auth-card">

        <h1>Login</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= View::e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="auth-form">

            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                required
                autocomplete="email"
            >

            <button type="submit" class="btn btn-accent">
                Login
            </button>

        </form>

        <p class="auth-footer">
            Don’t have an account?
            <a href="<?= BASE_PATH ?>/register">Register here</a>
        </p>

    </div>

</section>
