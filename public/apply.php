<?php
$pageTitle = "Application";
$pageStyles = "css/apply.css";
require_once '../app/templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$selectedRole = $_GET['role'] ?? 'player';
$themeClass = ($selectedRole === 'guard') ? 'theme-guard' : 'theme-player';

require_once '../db/connect.php';
require_once '../app/models/ApplicationModel.php';
$appModel = new ApplicationModel($conn);

if ($appModel->hasActiveApplication($_SESSION['user_id'])) {
    header("Location: status.php?error=already_applied");
    exit;
}
?>


<div class="auth-container <?= $themeClass ?>">
    <div class="form-card">
        <h2>Confirm Your Application</h2>
        <form action="../app/controllers/ApplyController.php" method="POST">
            <input type="hidden" name="role" value="<?= $selectedRole ?>">

            <div class="form-group">
                <label>Applicant Name</label>
                <input type="text" value="<?= htmlspecialchars($_SESSION['username']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Selected Role</label>
                <input type="text" value="<?= ucfirst($selectedRole) ?>" readonly>
            </div>

            <?php if ($selectedRole === 'player'): ?>
                <div class="form-group">
                    <label for="debt">Total Debt (Won)</label>
                    <input type="number" name="debt_amount" id="debt" placeholder="e.g. 456000000" required>
                </div>
                <div class="form-group">
                    <label for="reason">Reason for Joining</label>
                    <textarea name="reason" id="reason" rows="3" placeholder="Why do you want to play?" required></textarea>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label for="skill">Specialization</label>
                    <select name="combat_skill" id="skill" required>
                        <option value="" disabled selected>Select your skill...</option>
                        <option value="Marksman">Marksman</option>
                        <option value="CQC">Close Quarters Combat</option>
                        <option value="Surveillance">Surveillance</option>
                        <option value="Medical">Medical</option>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit">Submit</button>
        </form>
    </div>
</div>

<?php
require_once '../app/templates/footer.php';
?>