<?php
require_once '../../db/connect.php';
require_once '../../app/models/ApplicationModel.php';

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Admin History";

$appModel = new ApplicationModel($conn);
$history = $appModel->getHistoryApplications();

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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="history.php" class="active">History</a></li>
            <li><a href="../../app/controllers/auth/LogoutController.php" class="btn-logout-sidebar">Logout</a></li>
        </ul>
        <div class="toggle-handle"></div>
    </aside>

    <main class="content admin-container">
        <h2>Application History</h2>
        <div class="table-wrapper">
            <?php if (empty($history)): ?>
                <div class="empty-state">No history available.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i', strtotime($h['created_at'])) ?></td>
                                <td><?= htmlspecialchars($h['username']) ?></td>
                                <td><?= htmlspecialchars($h['role_name']) ?></td>
                                <td>
                                    <?php if ($h['status'] === 'accepted'): ?>
                                        <span style="color: #4caf50; font-weight: bold;">ACCEPTED</span>
                                    <?php else: ?>
                                        <span style="color: #f44336; font-weight: bold;">REJECTED</span>
                                    <?php endif; ?>
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