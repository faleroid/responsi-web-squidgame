<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$inPublic = strpos($_SERVER['PHP_SELF'], '/public/') !== false;
// Check if we are deeper (e.g. public/admin)
$isDeep = isset($isDeep) && $isDeep === true;

if ($isDeep) {
    // public/admin/dashboard.php
    $homeLink = '../../index.php';
    $statusLink = '../status.php';
    $adminLink = 'dashboard.php';
} elseif ($inPublic) {
    // public/something.php
    $homeLink = '../index.php';
    $statusLink = 'status.php';
    $adminLink = 'admin/dashboard.php';
} else {
    // index.php
    $homeLink = 'index.php';
    $statusLink = 'public/status.php';
    $adminLink = 'public/admin/dashboard.php';
}
?>
<section class="navbar">
    <div class="navbar-content">
        <a href="<?= $homeLink ?>" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">Home</a>
        <a href="<?= $statusLink ?>" class="<?= $currentPage == 'status.php' ? 'active' : '' ?>">Status</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="<?= $adminLink ?>" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">Admin</a>
        <?php endif; ?>
    </div>
</section>