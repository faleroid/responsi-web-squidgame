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
$statusCounts = $appModel->getStatusCounts();
$roleCounts = $appModel->getRoleCounts();
$acceptedApps = $appModel->getAcceptedApplications();

$activePlayers = array_filter($acceptedApps, function ($app) {
    return $app['role_name'] === 'Player';
});

$activeGuards = array_filter($acceptedApps, function ($app) {
    return $app['role_name'] !== 'Player';
});

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
            <li><a href="history.php">History</a></li>
            <li><a href="../../app/controllers/auth/LogoutController.php" class="btn-logout-sidebar">Logout</a></li>
        </ul>
        <div class="toggle-handle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </aside>

    <main class="content admin-container">
        <div class="statistics-content">
            <h2>Statistics</h2>
            <div class="stat-grid">
                <div class="stat-card total">
                    <h3>Total Applicants</h3>
                    <div class="count"><?= $statusCounts['total'] ?></div>
                </div>
                <div class="stat-card pending">
                    <h3>Pending</h3>
                    <div class="count"><?= $statusCounts['pending'] ?></div>
                </div>
                <div class="stat-card accepted">
                    <h3>Accepted</h3>
                    <div class="count"><?= $statusCounts['accepted'] ?></div>
                </div>
                <div class="stat-card rejected">
                    <h3>Rejected</h3>
                    <div class="count"><?= $statusCounts['eliminated'] ?></div>
                </div>
            </div>
        </div>

        <h2>Applicants Pending List</h2>
        <div class="table-wrapper">
            <?php if (empty($applications)): ?>
                <div class="empty-state">No data available.</div>
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
                                            <button type="submit" class="btn-reject">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <h2 style="margin-top: 40px;">Active Players</h2>
        <div class="table-wrapper">
            <?php if (empty($activePlayers)): ?>
                <div class="empty-state">No active players.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activePlayers as $acc): ?>
                            <tr>
                                <td><?= htmlspecialchars($acc['username']) ?></td>
                                <td>
                                    <span style="color: #4caf50; font-weight: bold;">ACTIVE</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <h2 style="margin-top: 40px;">Active Guards</h2>
        <div class="table-wrapper">
            <?php if (empty($activeGuards)): ?>
                <div class="empty-state">No active guards.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activeGuards as $acc): ?>
                            <tr>
                                <td><?= htmlspecialchars($acc['username']) ?></td>
                                <td>
                                    <span style="color: #4caf50; font-weight: bold;">ACTIVE</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const handle = document.querySelector('.toggle-handle');
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');

        // Toggle function
        function toggleSidebar() {
            if (handle) handle.classList.toggle('active');
            sidebar.classList.toggle('active');
        }

        if (handle) handle.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                const clickedInside = sidebar.contains(e.target) ||
                    (handle && handle.contains(e.target));

                if (!clickedInside && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    if (handle) handle.classList.remove('active');
                }
            }
        });
    });
</script>
</body>

</html>