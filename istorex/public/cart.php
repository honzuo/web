<?php
// public/cart.php
require_once __DIR__ . '/../controllers/CartController.php';

$controller = new CartController();
$controller->view();
?>