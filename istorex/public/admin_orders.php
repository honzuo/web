<?php
// public/admin_orders.php
require_once __DIR__ . '/../controllers/OrderController.php';

$controller = new OrderController();
$controller->adminOrders();
?>

