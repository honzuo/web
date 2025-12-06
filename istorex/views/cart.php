<?php
require_once __DIR__ . '/../lib/HtmlHelper.php';
include '_header.php';
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
    .cart-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .cart-table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
    }

    .cart-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .cart-product {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .cart-product img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }

    .qty-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        border-radius: 4px;
    }

    .qty-input {
        width: 40px;
        height: 30px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn-remove {
        color: #ff4444;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: underline;
    }

    .cart-summary {
        margin-top: 30px;
        background: white;
        padding: 25px;
        border-radius: 8px;
        text-align: right;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .total-label {
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .total-amount {
        font-size: 1.5rem;
        color: #007bff;
        font-weight: bold;
    }

    .btn-checkout {
        background: #007bff;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        margin-left: 20px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-checkout:hover {
        background: #0056b3;
    }
</style>

<div class="cart-container">
    <h2>Shopping Cart</h2>

    <?php if (!empty($cartItems)): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th width="40%">Product</th>
                    <th width="15%">Price</th>
                    <th width="20%">Quantity</th>
                    <th width="15%">Subtotal</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr data-vid="<?php echo $item['variant_id']; ?>">
                        <td>
                            <div class="cart-product">
                                <?php echo Html::img("../public/images/" . $item['image'], $item['name']); ?>
                                <div>
                                    <div><strong><?php echo htmlspecialchars($item['name']); ?></strong></div>
                                    <div style="font-size: 0.9em; color: #666;">Size: <?php echo htmlspecialchars($item['size']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>RM <?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <div class="qty-group">
                                <button class="qty-btn minus">-</button>
                                <input type="text" class="qty-input" value="<?php echo $item['qty']; ?>" readonly>
                                <button class="qty-btn plus">+</button>
                            </div>
                        </td>
                        <td class="subtotal">RM <?php echo number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <button class="btn-remove">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <span class="total-label">Grand Total:</span>
            <span class="total-amount">RM <?php echo number_format($grandTotal, 2); ?></span>
            <br><br>
            <a href="products.php" style="color: #666; text-decoration: none; margin-right: 20px;">Continue Shopping</a>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <h3>Your cart is empty 🛒</h3>
            <p>Go find some awesome products!</p>
            <br>
            <a href="products.php" class="btn-checkout">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        // 数量增加
        $('.plus').click(function() {
            updateQty($(this), 1);
        });

        // 数量减少
        $('.minus').click(function() {
            updateQty($(this), -1);
        });

        // 移除商品
        $('.btn-remove').click(function() {
            if (!confirm('Remove this item?')) return;
            var row = $(this).closest('tr');
            var vid = row.data('vid');

            $.ajax({
                url: '../public/cart_action.php',
                method: 'POST',
                data: {
                    action: 'remove',
                    variant_id: vid
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) location.reload();
                }
            });
        });

        function updateQty(btn, change) {
            var row = btn.closest('tr');
            var input = row.find('.qty-input');
            var vid = row.data('vid');
            var newQty = parseInt(input.val()) + change;

            if (newQty < 1) return;

            $.ajax({
                url: '../public/cart_action.php',
                method: 'POST',
                data: {
                    action: 'update',
                    variant_id: vid,
                    quantity: newQty
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) location.reload(); // 简单起见，刷新页面重新计算总价
                }
            });
        }
    });
</script>

<?php include '_footer.php'; ?>