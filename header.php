<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = "https://webik.ms.mff.cuni.cz/~53737289/semestral-project";

$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['user']['username']) : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Eventify</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/styles.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
        header {
            width: 100%;
            background: #1E1E2F;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 24px;
            box-sizing: border-box;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #00E5FF;
            text-decoration: none;
        }
        
        nav a {
            color: #ffffff;
            margin-left: 18px;
            text-decoration: none;
            font-size: 18px;
        }

        nav a:hover {
            color: #00E5FF;
        }

        .nav-left, .nav-right {
            display: flex;
            align-items: center;
        }
        .logout-btn {
            background: #FF5252;
            color: white;
            border: none;
            padding: 6px 12px;
            margin-left: 18px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
        }
        .logout-btn:hover {
            background: #FF1744;
        }
    </style>
</head>
<body>
<header>
    <div class="nav-left">
        <a class="logo" href="<?php echo $baseUrl; ?>/">Eventify</a>

        <nav>
            <a href="<?php echo $baseUrl; ?>/">Home</a>
            <a href="<?php echo $baseUrl; ?>/events">Events</a>
        </nav>
    </div>

    <div class="nav-right">
        <nav>
            <?php if (!$isLoggedIn): ?>
                <a href="<?php echo $baseUrl; ?>/login">Login</a>
            <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/settings"><?php echo $username; ?></a>
                <a href="<?php echo $baseUrl; ?>/events/new">Create Event</a>
                <a href="<?php echo $baseUrl; ?>/events/mine">My Events</a>
                <form action="<?php echo $baseUrl; ?>/logout.php" method="POST" style="display:inline;">
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            <?php endif; ?>
        </nav>
    </div>
</header>
