$(document).ready(function () {

    // Add to Cart 点击事件
    $('.btn-add-cart').on('click', function (e) {
        e.preventDefault();

        // ⭐ 1. 检查登录状态 (isLoggedIn 来自 _header.php)
        if (!isLoggedIn) {
            alert("🔒 Please login to start shopping!");
            window.location.href = 'login.php'; // 跳转到登录页
            return; // 终止后续代码执行
        }

        var btn = $(this);
        var variantId = btn.data('variant-id');

        // 2. 检查是否选择了规格
        if (!variantId) {
            alert("⚠️ Please select an option first.");
            return;
        }

        // 3. 按钮加载状态动画
        var originalText = btn.text();
        btn.text('Adding...').prop('disabled', true);

        // 4. 发送 AJAX
        $.ajax({
            url: '../public/cart_action.php',
            method: 'POST',
            data: {
                action: 'add',
                variant_id: variantId,
                quantity: 1
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // 询问用户去结算还是继续购物
                    if (confirm('✅ Product added! Go to Cart?')) {
                        window.location.href = 'cart.php';
                    }
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function () {
                alert('Connection Error');
            },
            complete: function () {
                // 恢复按钮
                btn.text(originalText).prop('disabled', false);
            }
        });
    });

    // ... (Remove 的逻辑保持不变) ...
    $('.btn-remove').on('click', function () {
        if (!confirm('Remove item?')) return;
        var btn = $(this);
        var vid = btn.data('id');
        // 或者是 data-vid，取决于你在 cart.php 怎么写的，请保持一致
        // 如果 cart.php 是 <tr data-vid="..."> <button class="btn-remove">
        // 那里取 ID 应该是 var vid = btn.closest('tr').data('vid');

        // 修正后的 Remove 逻辑推荐写法：
        var row = btn.closest('tr');
        var vid = row.data('vid');

        $.ajax({
            url: '../public/cart_action.php',
            method: 'POST',
            data: { action: 'remove', variant_id: vid },
            dataType: 'json',
            success: function (resp) {
                if (resp.success) location.reload();
            }
        });
    });
});