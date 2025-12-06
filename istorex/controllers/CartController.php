<?php
// controllers/CartController.php
require_once __DIR__ . '/../models/product.php';

class CartController
{

    private $productModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->productModel = new Product();
    }

    // API: 添加商品
    public function add($variantId, $quantity = 1)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $vid = intval($variantId);
        $qty = intval($quantity);

        if (isset($_SESSION['cart'][$vid])) {
            $_SESSION['cart'][$vid] += $qty;
        } else {
            $_SESSION['cart'][$vid] = $qty;
        }

        return ['success' => true, 'message' => 'Added to cart', 'count' => array_sum($_SESSION['cart'])];
    }

    // API: 更新数量 (用于购物车页面的 +/- 按钮)
    public function update($variantId, $quantity)
    {
        $vid = intval($variantId);
        $qty = intval($quantity);

        if ($qty <= 0) {
            return $this->remove($vid);
        }

        if (isset($_SESSION['cart'][$vid])) {
            $_SESSION['cart'][$vid] = $qty;
            return ['success' => true, 'message' => 'Cart updated'];
        }

        return ['success' => false, 'message' => 'Item not found'];
    }

    // API: 移除商品
    public function remove($variantId)
    {
        $vid = intval($variantId);
        if (isset($_SESSION['cart'][$vid])) {
            unset($_SESSION['cart'][$vid]);
            return ['success' => true, 'message' => 'Item removed'];
        }
        return ['success' => false, 'message' => 'Item not found'];
    }

    // View: 准备数据并显示页面
    public function view()
    {
        $cartItems = [];
        $grandTotal = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $vid => $qty) {
                // ⭐ 从数据库获取实时价格和详情
                $item = $this->productModel->getVariantDetails($vid);

                if ($item) {
                    $subtotal = $item['price'] * $qty;
                    $grandTotal += $subtotal;

                    $cartItems[] = [
                        'variant_id' => $vid,
                        'name' => $item['name'],
                        'size' => $item['size'],
                        'image' => $item['image'],
                        'price' => $item['price'],
                        'qty' => $qty,
                        'subtotal' => $subtotal
                    ];
                }
            }
        }

        // 加载视图文件
        require_once __DIR__ . '/../views/cart.php';
    }
}
?>