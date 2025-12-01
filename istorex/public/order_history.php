<?php
// public/order_history.php
require_once __DIR__ . '/../controllers/OrderController.php';

$controller = new OrderController();
$controller->orderHistory();
?>

