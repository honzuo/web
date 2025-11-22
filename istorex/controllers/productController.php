<?php
// controllers/ProductController.php
require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/category.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function home() {
        $products = $this->productModel->getHotProducts(4);
        $categories = $this->categoryModel->getCategories();
        
        require_once __DIR__ . '/../views/home.php';
    }
    
    public function detail() {
        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $product = $this->productModel->getProductById($productId);
        
        require_once __DIR__ . '/../views/product_detail.php';
    }
    
    public function category() {
        $categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $category = $this->categoryModel->getCategoryById($categoryId);
        $products = $this->productModel->getProductsByCategory($categoryId);
        
        require_once __DIR__ . '/../views/category.php';
    }
    
    public function products() {
        $products = $this->productModel->getAllProducts();
        
        require_once __DIR__ . '/../views/products.php';
    }
}
?>