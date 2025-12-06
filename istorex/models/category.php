<?php
// models/Category.php
// 严格遵守：使用 PDO 语法

require_once __DIR__ . '/../config/database.php';

class Category
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // 获取所有分类
    public function getCategories()
    {
        // PDO 写法
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // ❌ 错误写法 (MySQLi): while($row = $result->fetch_assoc())
        // ✅ 正确写法 (PDO): 直接 fetchAll 获取所有
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 根据 ID 获取单个分类
    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        // ✅ 正确写法 (PDO): fetch 获取单行
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>