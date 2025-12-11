<?php
require_once '../../db/connect.php';
require_once '../../app/models/ApplicationModel.php';

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Admin Dashboard";

$appModel = new ApplicationModel($conn);
$applications = $appModel->getAllPendingApplications();

$cssPathPrefix = "../";
$pageStyles = "css/admin.css";

require_once '../../app/templates/header.php';
?>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../assets/img/logo.png" alt="logo" width="150px">
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="../../app/controllers/auth/LogoutController.php" class="btn-logout-sidebar">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="content admin-container">
        <h2>Applicants</h2>

        <div class="table-wrapper">
            <?php if (empty($applications)): ?>
                <div class="empty-state">No pending applications at the moment.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i', strtotime($app['created_at'])) ?></td>
                                <td><?= htmlspecialchars($app['username']) ?></td>
                                <td><?= htmlspecialchars($app['role_name']) ?></td>
                                <td>
                                    <?php if ($app['role_name'] === 'Player'): ?>
                                        Debt: <?= number_format($app['debt_amount']) ?><br>
                                        Reason: <?= htmlspecialchars($app['reason_to_join']) ?>
                                    <?php else: ?>
                                        Skill: <?= htmlspecialchars($app['combat_skill']) ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-form">
                                        <form action="../../app/controllers/AdminController.php" method="POST">
                                            <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="btn-accept">Accept</button>
                                        </form>
                                        <form action="../../app/controllers/AdminController.php" method="POST">
                                            <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn-reject">Eliminate</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>

</html>