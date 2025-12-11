<?php
$pageTitle = "Login";
$pageStyles = "css/auth.css";
require_once '../app/templates/header.php';
?>

<div class="auth-container">
    <div class="auth-wrapper">
        <div class="img-box">
            <img src="assets/img/logo.png" alt="character">
        </div>

        <div class="form-box">
            <h2>Login</h2>

            <?php if (isset($_GET['error_login'])): ?>
                <p class="notification error"><?= htmlspecialchars($_GET['error_login']) ?></p>
            <?php endif; ?>

            <form class="form-wrapper" action="../app/controllers/auth/LoginController.php" method="POST">
                <div class="form-group">
                    <label for="login_email">Email</label>
                    <input type="email" id="login_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="login_password">Password</label>
                    <input type="password" id="login_password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>

            <p style="text-align: center; margin-top: 20px;">Don't have an account? <a href="register.php"> Register
                    Now</a>
            </p>
        </div>
    </div>
</div>

<?php
require_once '../app/templates/footer.php';
?>