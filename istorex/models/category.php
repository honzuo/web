<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getCategories() {
        $result = $this->conn->query("SELECT * FROM categories");
        
        $categories = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        return $categories;
    }
    
    public function getCategoryById($categoryId) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        
        return $category;
    }
}
?>