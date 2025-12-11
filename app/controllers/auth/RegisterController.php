<?php
session_start();
require_once '../../../db/connect.php';
require_once '../../validator/UserPayloadValidator.php';
require_once '../../models/UserModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password-confirm'];

if (UserPayloadValidator::isPayloadEmpty([$username, $email, $password, $password_confirm])) {
    header('Location: ../../../public/register.php?error=Fields cannot be empty');
    exit;
}

if (!UserPayloadValidator::isValidEmail($email)) {
    header('Location: ../../../public/register.php?error=Invalid email');
    exit;
}

if (!UserPayloadValidator::passwordsMatch($password, $password_confirm)) {
    header('Location: ../../../public/register.php?error=Password confirmation does not match');
    exit;
}

$userModel = new UserModel($conn);
$existingUser = $userModel->findUser($email, $username);

if ($existingUser) {
    header('Location: ../../../public/register.php?error=Email or Username already registered');
    exit;
}

$result = $userModel->createNewUser($username, $email, $password);

if ($result) {
    header('Location: ../../../public/register.php?status=reg_success');
} else {
    header('Location: ../../../public/register.php?error=Registration failed, please try again');
}

mysqli_close($conn);
exit;