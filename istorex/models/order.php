<?php
// models/order.php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    // Get all orders for a specific user
    public function getOrdersByUserId($userId) {
        $stmt = $this->conn->prepare("
            SELECT o.*, 
                   COALESCE(COUNT(oi.id), 0) as item_count,
                   COALESCE(o.total, SUM(oi.qty * oi.price), 0) as total_amount
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            WHERE o.user_id = ?
            GROUP BY o.id
            ORDER BY o.order_date DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $stmt->close();
        
        return $orders;
    }
    
    // Get all orders for admin
    public function getAllOrders() {
        $result = $this->conn->query("
            SELECT o.*, 
                   u.username,
                   u.full_name,
                   u.email,
                   COALESCE(COUNT(oi.id), 0) as item_count,
                   COALESCE(o.total, SUM(oi.qty * oi.price), 0) as total_amount
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
            GROUP BY o.id
            ORDER BY o.order_date DESC
        ");
        
        $orders = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        
        return $orders;
    }
    
    // Get order details by order ID
    public function getOrderById($orderId) {
        $stmt = $this->conn->prepare("
            SELECT o.*, 
                   u.username,
                   u.full_name,
                   u.email,
                   u.phone,
                   u.address
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        
        return $order;
    }
    
    // Get order items by order ID
    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $stmt->close();
        
        return $items;
    }
    
    // Get order with items (complete order details)
    public function getOrderWithItems($orderId) {
        $order = $this->getOrderById($orderId);
        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
            // Use total from order table, or calculate if not set
            if (empty($order['total']) || $order['total'] == 0) {
                $total = 0;
                foreach ($order['items'] as $item) {
                    $total += $item['qty'] * $item['price'];
                }
                $order['total'] = $total;
            }
        }
        return $order;
    }
    
    // Update order status (for admin)
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $orderId);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>

