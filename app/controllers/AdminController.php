<?php
require_once __DIR__ . '/../../db/connect.php';
require_once __DIR__ . '/../models/ApplicationModel.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../public/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $appId = $_POST['application_id'] ?? '';

    if ($action && $appId) {
        $appModel = new ApplicationModel($conn);
        $newStatus = ($action === 'accept') ? 'accepted' : 'eliminated';

        if ($appModel->updateStatus($appId, $newStatus)) {
            $msg = 'success';
        } else {
            $msg = 'error';
        }
    } else {
        $msg = 'invalid_request';
    }

    header("Location: ../../public/admin/dashboard.php?msg=$msg");
    exit;
}
