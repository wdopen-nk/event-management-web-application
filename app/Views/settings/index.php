<section class="site-content settings-page">

    <h1 class="page-title">Account Settings</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= View::e($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success"><?= View::e($success) ?></div>
    <?php endif; ?>

    <!-- Edit profile -->
    <div class="card settings-section">
        <h2>Edit Profile</h2>

        <form method="post" class="settings-form">
            <input type="hidden" name="_csrf" value="<?= View::e(Csrf::token()) ?>">

            <label>
                Full name:
                <input type="text"
                       name="full_name"
                       required
                       value="<?= View::e($user['username']) ?>">
            </label>

            <label>
                Email (cannot be changed):
                <input type="email"
                       value="<?= View::e($user['email']) ?>"
                       disabled>
            </label>

            <button type="submit"
                    name="update_profile"
                    class="btn btn-accent btn-full">
                Update Profile
            </button>
        </form>
    </div>

    <hr class="section-divider">

    <!-- Delete account -->
    <div class="card settings-section">
        <h2>Delete Account</h2>

        <form method="post"
              onsubmit="return confirm('Are you sure you want to delete your account?');">

            <input type="hidden" name="_csrf" value="<?= View::e(Csrf::token()) ?>">

            <button type="submit"
                    name="delete_account"
                    class="btn btn-danger">
                Delete My Account
            </button>
        </form>
    </div>

</section>
