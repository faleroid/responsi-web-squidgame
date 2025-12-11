<?php
$pageStylesIndex = "public/css/main.css";
require_once 'app/templates/header.php';

// Cek Admin Redirect
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: public/admin/dashboard.php");
    exit;
}
?>

<div class="content">
    <?php if (isset($_GET["status"]) && $_GET["status"] == "logout_success"): ?>
        <p class="notification success">Anda berhasil logout.</p>
    <?php endif; ?>

    <?php require_once 'app/templates/navbar.php'; ?>

    <h1>CHOOSE YOUR FATE</h1>

    <div class="top">
        <p>Get ready for the biggest game & prize!</p>
        <h2>₩45,6 billion</h2>
    </div>

    <div class="card card-guard">
        <div class="card-content">
            <h2>The Guards</h2>
            <p>
                Order. Authority. Control. The world is built on rules, and you are here to enforce them.
                Eliminate the weak, maintain the structure, and answer only to the Front Man.
                Discipline is your weapon. Are you ready to serve?
            </p>
            <a href="public/apply.php?role=guard" class="btn">Join the System</a>
        </div>
    </div>

    <div class="card card-player">
        <div class="card-content">
            <h2>The Players</h2>
            <p>
                Drowning in debt? No way out? We offer a lifeline.
                High stakes, higher rewards. Survive 6 games, outlast 455 others, and claim the
                ₩45.6 Billion prize. Your life is the currency. Do you have the courage to play?
            </p>
            <a href="public/apply.php?role=player" class="btn">Enter the Game</a>
        </div>
    </div>

</div>


<?php
require_once 'app/templates/footer.php';
?>