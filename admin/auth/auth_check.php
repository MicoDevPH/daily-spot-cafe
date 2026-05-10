<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /daily-spot-cafe/admin/auth/login.php");
    exit();
}

// Ensure all details are in session (for users who were logged in before the update)
if (!isset($_SESSION['email']) || !isset($_SESSION['first_name'])) {
    require_once __DIR__ . '/../../src/controller/UserController.php';
    $userController = new UserController();
    $userDetails = $userController->show($_SESSION['user_id'])['details'];
    
    if ($userDetails) {
        $_SESSION['first_name'] = $userDetails['first_name'] ?? '';
        $_SESSION['last_name'] = $userDetails['last_name'] ?? '';
        $_SESSION['email'] = $userDetails['email'] ?? '';
        $_SESSION['phone_number'] = $userDetails['phone_number'] ?? '';
        $_SESSION['full_name'] = $userDetails['full_name'] ?? $_SESSION['username'];
    }
}
?>
