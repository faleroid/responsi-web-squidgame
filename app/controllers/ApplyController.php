<?php
require_once __DIR__ . '/../../db/connect.php';
require_once __DIR__ . '/../models/ApplicationModel.php';
require_once __DIR__ . '/../validator/ApplicationValidator.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/login.php");
    exit;
}

$appModel = new ApplicationModel($conn);
$userId = $_SESSION['user_id'];

if ($appModel->hasActiveApplication($userId)) {
    header("Location: ../../public/status.php?error=already_applied");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];

    $positionId = ($role === 'player') ? 1 : 2;

    $data = [
        'user_id' => $userId,
        'position_id' => $positionId,
        'debt_amount' => $_POST['debt_amount'] ?? null,
        'reason' => $_POST['reason'] ?? null,
        'combat_skill' => $_POST['combat_skill'] ?? null
    ];

    $isValid = ($role === 'player')
        ? ApplicationValidator::validatePlayer($data)
        : ApplicationValidator::validateGuard($data);

    if ($isValid === true) {
        if ($appModel->isSlotAvailable($positionId)) {
            $finalStatus = 'pending';
            $msg = 'wait_approval';
        } else {
            $finalStatus = 'eliminated';
            $msg = 'full';
        }

        if ($appModel->createApplication($data, $finalStatus)) {
            header("Location: ../../public/status.php?status=$finalStatus&msg=$msg");
            exit;
        } else {
            echo "Terjadi kesalahan sistem.";
        }

    } else {
        echo "Error: " . $isValid;
    }
}