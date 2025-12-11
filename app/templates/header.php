<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Responsi Web' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <?php
    $cssPathPrefix = isset($cssPathPrefix) ? $cssPathPrefix : '';
    ?>

    <?php if (isset($pageStyles)): ?>
        <link rel="stylesheet" href="<?= $cssPathPrefix ?>css/style.css">
        <link rel="stylesheet" href="<?= $cssPathPrefix ?>css/navbar.css">
        <link rel="stylesheet" href="<?= $cssPathPrefix ?><?= $pageStyles ?>">
    <?php endif; ?>

    <?php if (isset($pageStylesIndex)): ?>
        <link rel="stylesheet" href="public/css/style.css">
        <link rel="stylesheet" href="public/css/navbar.css">
        <link rel="stylesheet" href="<?= $pageStylesIndex ?>">
    <?php endif; ?>
</head>

<body>
    <header>
        <nav>
            <?php if (isset($pageStylesIndex)): ?>
                <img src="public/assets/img/logo.png" alt="logo" width="100px">
            <?php else: ?>
                <a href="<?= $cssPathPrefix ?>../index.php" class="logo">
                    <img src="<?= $cssPathPrefix ?>assets/img/logo.png" alt="logo" width="100px">
                </a>
            <?php endif; ?>
            <ul class="nav-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($pageStylesIndex)): ?>
                        <li><a href="app/controllers/auth/LogoutController.php" class="btn-logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?= $cssPathPrefix ?>../app/controllers/auth/LogoutController.php"
                                class="btn-logout">Logout</a></li>
                    <?php endif; ?>

                <?php else: ?>
                    <?php if (isset($pageStylesIndex)): ?>
                        <li>
                            <div class="btn-auth">
                                <a href="public/login.php" class="btn-login">Login</a>
                                <a href="public/register.php" class="btn-register">Register</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li>
                            <div class="btn-auth">
                                <a href="login.php" class="btn-login">Login</a>
                                <a href="register.php" class="btn-register">Register</a>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main class="container"></main>