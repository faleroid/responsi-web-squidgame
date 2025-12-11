<?php
$pageTitle = "Register";
$pageStyles = "css/auth.css";
require_once '../app/templates/header.php';

if (isset($_SESSION['user_id'])) {
    header('Location: journal.php');
    exit;
}
?>

<div class="auth-container">
    <div class="auth-wrapper">
        <div class="form-box">
            <h2>Register</h2>

            <?php if (isset($_GET['error'])): ?>
                <p class="notification error"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'reg_success'): ?>
                <p class="notification success">Registration successful! Please login.</p>
            <?php endif; ?>


            <form action="../app/controllers/auth/RegisterController.php" method="POST" class="form-wrapper">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <input type="password" id="password-confirm" name="password-confirm" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Register</button>
                </div>
            </form>
            <p style="text-align: center; margin-top: 20px;">Already have an account? <a href="login.php"> Login Now</a>
            </p>
        </div>
        <div class="img-box">
            <img src="assets/img/logo.png" alt="character">
        </div>
    </div>
</div>

<?php
require_once '../app/templates/footer.php';
?>