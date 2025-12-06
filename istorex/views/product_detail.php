<?php
require_once __DIR__ . '/../lib/HtmlHelper.php';
// 还需要手动获取 variants，因为 Controller 可能没传，或者你在 Controller 里加
// 为了简单，我们直接在这里调用 Model (虽然不完美但能跑)
require_once __DIR__ . '/../models/product.php';
$productModel = new Product();
$variants = $productModel->getProductVariants($product['id']);

include '_header.php';
?>

<link rel="stylesheet" href="../public/css/products_detail.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="container">
    <?php if ($product): ?>
        <div class="breadcrumb">
            <a href="home.php">Home</a> /
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="product-detail">
            <div class="product-image">
                <?php echo Html::img("../public/images/" . $product['image'], $product['name']); ?>
            </div>

            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>

                <p class="product-price" id="display-price">
                    <?php
                    // 默认显示最低价
                    if (!empty($variants)) {
                        echo 'RM ' . number_format($variants[0]['price'], 2);
                    } else {
                        echo 'Out of Stock';
                    }
                    ?>
                </p>

                <div class="form-group" style="margin: 20px 0;">
                    <label><strong>Select Option:</strong></label>
                    <select id="variant-select" class="form-control" style="padding: 10px; width: 100%; margin-top: 5px;">
                        <?php foreach ($variants as $v): ?>
                            <option value="<?php echo $v['id']; ?>" data-price="<?php echo $v['price']; ?>">
                                <?php echo htmlspecialchars($v['size']); ?> - RM <?php echo $v['price']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="product-actions">
                    <button class="btn-primary btn-add-cart" id="btn-add-cart"
                        data-variant-id="<?php echo !empty($variants) ? $variants[0]['id'] : ''; ?>">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Product not found</p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        // 1. 监听规格选择变化
        $('#variant-select').on('change', function() {
            // 获取选中的 option
            var selected = $(this).find(':selected');
            var price = selected.data('price');
            var variantId = $(this).val();

            // 更新价格显示
            $('#display-price').text('RM ' + parseFloat(price).toFixed(2));

            // 更新按钮的 data-variant-id
            $('#btn-add-cart').data('variant-id', variantId);
        });
    });
</script>

<script src="../public/js/cart.js"></script>

<?php include '_footer.php'; ?>