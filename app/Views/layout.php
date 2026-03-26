<?php
/** @var string $content */
/** @var array|null $user */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Eventify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/styles.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">

        <div class="nav-left">
            <a class="logo" href="<?= BASE_PATH ?>/">Eventify</a>

            <nav class="nav">
                <a href="<?= BASE_PATH ?>/">Home</a>
                <a href="<?= BASE_PATH ?>/events">Events</a>
            </nav>
        </div>

        <div class="nav-right">
            <nav class="nav">
                <?php if (empty($user)): ?>
                    <a href="<?= BASE_PATH ?>/login">Login</a>
                <?php else: ?>
                    <a href="<?= BASE_PATH ?>/settings">
                        <?= View::e($user['username']) ?>
                    </a>

                    <a href="<?= BASE_PATH ?>/events/new">Create Event</a>
                    <a href="<?= BASE_PATH ?>/events/mine">My Events</a>

                    <form action="<?= BASE_PATH ?>/logout" method="post" style="display:inline;">
                        <input type="hidden" name="_csrf" value="<?= View::e(Csrf::token()) ?>">
                        <button class="logout-btn" type="submit">Logout</button>
                    </form>
                <?php endif; ?>
            </nav>
        </div>

    </div>
</header>

<main class="site-content">
    <?= $content ?>
</main>

</body>
</html>
