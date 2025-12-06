<?php
// models/product.php
require_once __DIR__ . '/../config/database.php';

class Product
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // 1. 获取列表 (首页/分类页)：必须 JOIN variant 表取最低价
    public function getHotProducts($limit = 6)
    {
        //以此为例，获取最低价显示为 min_price
        $sql = "
            SELECT p.id, p.name, p.image, MIN(v.price) as price 
            FROM products p 
            JOIN product_variant v ON p.id = v.product_id 
            GROUP BY p.id 
            LIMIT :limit
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 获取分类产品
    public function getProductsByCategory($categoryId)
    {
        $sql = "
            SELECT p.id, p.name, p.image, MIN(v.price) as price 
            FROM products p 
            JOIN product_variant v ON p.id = v.product_id 
            WHERE p.category_id = :cid
            GROUP BY p.id 
            ORDER BY p.name ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cid' => $categoryId]);
        return $stmt->fetchAll();
    }

    // 2. 获取详情：不需要 JOIN 价格，因为价格有多个，下面单独取
    public function getProductById($productId)
    {
        $sql = "
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $productId]);
        return $stmt->fetch();
    }

    // 3. ⭐ 新增方法：获取某个产品的所有规格 (用于详情页下拉框)
    public function getProductVariants($productId)
    {
        $sql = "SELECT * FROM product_variant WHERE product_id = :pid ORDER BY price ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll();
    }

    public function getVariantDetails($variantId)
    {
        // 使用 PDO JOIN 查询：从 variants 表连接 products 表
        $sql = "
            SELECT 
                v.id as variant_id, 
                v.price, 
                v.size, 
                p.id as product_id, 
                p.name, 
                p.image 
            FROM product_variant v
            JOIN products p ON v.product_id = p.id
            WHERE v.id = :vid
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':vid' => $variantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
