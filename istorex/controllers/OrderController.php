<?php
// controllers/OrderController.php
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/User.php';

class OrderController {
    private $orderModel;
    private $userModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->orderModel = new Order();
        $this->userModel = new User();
    }
    
    // Check if user is admin
    private function isAdmin() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        
        // Check if user has admin role (assuming role field exists in users table)
        // For now, we'll check if username is 'admin' or if there's a role field
        // You may need to adjust this based on your actual admin check
        return isset($user['role']) && $user['role'] === 'admin';
    }
    
    // User order history page
    public function orderHistory() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
        // Get order details if order ID is provided
        $orderDetails = null;
        if (isset($_GET['id'])) {
            $orderId = intval($_GET['id']);
            $orderDetails = $this->orderModel->getOrderWithItems($orderId);
            
            // Verify that the order belongs to the logged-in user
            if ($orderDetails && $orderDetails['user_id'] != $userId) {
                $orderDetails = null;
            }
        }
        
        require_once __DIR__ . '/../views/order_history.php';
    }
    
    // Admin / staff order listing page
    public function adminOrders() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }
        
        // Allow both admin and staff to access order listing
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        if (!isset($user['role']) || !in_array($user['role'], ['admin', 'staff'])) {
            header('Location: profile.php');
            exit();
        }
        
        $orders = $this->orderModel->getAllOrders();
        
        // Get order details if order ID is provided
        $orderDetails = null;
        if (isset($_GET['id'])) {
            $orderId = intval($_GET['id']);
            $orderDetails = $this->orderModel->getOrderWithItems($orderId);
        }
        
        // Handle status update
        $message = '';
        $messageType = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
            $orderId = intval($_POST['order_id']);
            $status = $_POST['status'] ?? '';
            
            if ($this->orderModel->updateOrderStatus($orderId, $status)) {
                $message = 'Order status updated successfully';
                $messageType = 'success';
                // Refresh order details if viewing the same order
                if ($orderDetails && $orderDetails['id'] == $orderId) {
                    $orderDetails = $this->orderModel->getOrderWithItems($orderId);
                }
            } else {
                $message = 'Failed to update order status';
                $messageType = 'error';
            }
        }
        
        require_once __DIR__ . '/../views/admin_orders.php';
    }
}
?>

