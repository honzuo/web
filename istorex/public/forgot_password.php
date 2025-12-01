<?php
// public/forgot_password.php
require_once __DIR__ . '/../controllers/UserController.php';

$controller = new UserController();
$controller->forgotPassword();
?>