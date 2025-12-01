<?php
include '_header.php';
?>

<link rel="stylesheet" href="../public/css/order_history.css">

<div class="container order-container">
    <h1>My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p>You haven't placed any orders yet.</p>
            <a href="home.php" class="btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="orders-layout">
            <!-- Orders List -->
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                                <p class="order-date"><?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></p>
                            </div>
                            <div class="order-status">
                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="order-summary">
                            <p><strong>Items:</strong> <?php echo $order['item_count']; ?> item(s)</p>
                            <p><strong>Total:</strong> RM <?php echo number_format($order['total_amount'], 2); ?></p>
                        </div>
                        
                        <div class="order-actions">
                            <a href="order_history.php?id=<?php echo $order['id']; ?>" class="btn-secondary">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Order Details Sidebar -->
            <?php if ($orderDetails): ?>
                <div class="order-details-panel">
                    <div class="details-header">
                        <h2>Order Details</h2>
                        <a href="order_history.php" class="close-btn">&times;</a>
                    </div>
                    
                    <div class="details-content">
                        <div class="detail-section">
                            <h3>Order Information</h3>
                            <p><strong>Order #:</strong> <?php echo str_pad($orderDetails['id'], 6, '0', STR_PAD_LEFT); ?></p>
                            <p><strong>Date:</strong> <?php echo date('M d, Y h:i A', strtotime($orderDetails['order_date'])); ?></p>
                            <p><strong>Status:</strong> 
                                <span class="status-badge status-<?php echo strtolower($orderDetails['status']); ?>">
                                    <?php echo htmlspecialchars($orderDetails['status']); ?>
                                </span>
                            </p>
                        </div>
                        
                        <div class="detail-section">
                            <h3>Shipping Address</h3>
                            <p><?php echo htmlspecialchars($orderDetails['full_name'] ?? 'N/A'); ?></p>
                            <p><?php echo htmlspecialchars($orderDetails['address'] ?? 'N/A'); ?></p>
                            <p><?php echo htmlspecialchars($orderDetails['phone'] ?? 'N/A'); ?></p>
                        </div>
                        
                        <div class="detail-section">
                            <h3>Order Items</h3>
                            <div class="order-items">
                                <?php foreach ($orderDetails['items'] as $item): ?>
                                    <div class="order-item">
                                        <img src="../public/images/<?php echo htmlspecialchars($item['product_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                             class="item-image">
                                        <div class="item-info">
                                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                            <p>Quantity: <?php echo $item['qty']; ?></p>
                                            <p>Price: RM <?php echo number_format($item['price'], 2); ?></p>
                                        </div>
                                        <div class="item-total">
                                            <p>RM <?php echo number_format($item['qty'] * $item['price'], 2); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="order-total">
                                <p><strong>Total Amount:</strong> RM <?php echo number_format($orderDetails['total'] ?? 0, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php
include '_footer.php';
?>

