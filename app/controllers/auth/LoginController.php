<?php
session_start();
require_once '../../../db/connect.php';
require_once '../../validator/UserPayloadValidator.php';
require_once '../../models/UserModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

if (UserPayloadValidator::isPayloadEmpty([$email, $password])) {
    header('Location: ../../../public/login.php?error_login=Email and password cannot be empty');
    exit;
}

if (!UserPayloadValidator::isValidEmail($email)) {
    header('Location: ../../../public/login.php?error_login=Invalid email');
    exit;
}

$userModel = new UserModel($conn);
$userExist = $userModel->findUser($email);

if ($userExist && password_verify($password, $userExist['password_hash'])) {
    $_SESSION['user_id'] = $userExist['user_id'];
    $_SESSION['username'] = $userExist['username'];
    $_SESSION['role'] = $userExist['role'];

    if ($userExist['role'] === 'admin') {
        header('Location: ../../../public/admin/dashboard.php');
    } else {
        header('Location: ../../../index.php');
    }
} else {
    header('Location: ../../../public/login.php?error_login=Invalid email or password');
}

mysqli_close($conn);
exit;