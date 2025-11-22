<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getHotProducts($limit = 6) {
        $sql = "SELECT id, name, price, image FROM products LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getProductsByCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }

    public function getProductById($productId) {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        
        if ($product) {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $specs = [];
            while ($row = $result->fetch_assoc()) {
                $specs[] = $row;
            }
            $product['specs'] = $specs;
        }
        
        return $product;
    }
    
    public function getAllProducts() {
        $result = $this->conn->query("SELECT * FROM products ORDER BY name ASC");
        
        $products = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
}
?>