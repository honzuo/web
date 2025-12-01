<?php
// public/verify_otp.php
require_once __DIR__ . '/../controllers/UserController.php';

$controller = new UserController();
$controller->verifyOTP();
?>