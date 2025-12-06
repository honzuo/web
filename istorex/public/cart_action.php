<?php
// public/cart_action.php
require_once __DIR__ . '/../controllers/CartController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $controller = new CartController();
    $vid = intval($_POST['variant_id'] ?? 0);

    if ($vid > 0) {
        if ($action === 'add') {
            $qty = intval($_POST['quantity'] ?? 1);
            echo json_encode($controller->add($vid, $qty));
            exit;
        }

        if ($action === 'update') {
            $qty = intval($_POST['quantity']);
            echo json_encode($controller->update($vid, $qty));
            exit;
        }

        if ($action === 'remove') {
            echo json_encode($controller->remove($vid));
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid Request']);
?>