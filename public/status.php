<?php
$pageTitle = "Application Status";
$pageStyles = "css/status.css";
require_once '../app/templates/header.php';

require_once '../db/connect.php';
require_once '../app/models/ApplicationModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit;
}

$appModel = new ApplicationModel($conn);
$application = $appModel->getApplication($_SESSION['user_id']);

$statusClass = 'status-warning';
$title = 'NOT APPLIED';
$message = 'You have not submitted an application yet. <br>Please choose your fate.';

if ($application) {
    $dbStatus = $application['status'];

    if ($dbStatus === 'accepted') {
        $statusClass = 'status-accepted';
        $title = 'ACCEPTED';
        $message = 'Congratulations. You have been accepted into the game. <br>Wait for the pickup vehicle at the designated location at midnight.';
    } elseif ($dbStatus === 'eliminated') {
        $statusClass = 'status-eliminated';
        $title = 'ELIMINATED';
        $message = 'We regret to inform you that the quota is full. <br>Your application has been rejected.';
    } elseif ($dbStatus === 'pending') {
        $statusClass = 'status-pending';
        $title = 'Processing';
        $message = 'Your application is being processed. Please wait.';
    }
} else {
    if (($_GET['error'] ?? '') === 'already_applied') {
        $statusClass = 'status-warning';
        $title = 'Application Exists';
        $message = 'You have already submitted an application.';
    }
}
?>
<div class="content">
    <?php require_once '../app/templates/navbar.php'; ?>

    <div class="status-container">
        <div class="status-card <?= $statusClass ?>">

            <h2><?= $title ?></h2>
            <p class="status-message"><?= $message ?></p>

            <a href="../index.php" class="btn-home">Return to Home</a>
        </div>
    </div>
</div>

<?php
require_once '../app/templates/footer.php';
?>