<?php
include '_header.php';
?>

<link rel="stylesheet" href="../public/css/admin_orders.css">

<div class="container admin-container">
    <h1>Order Management</h1>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="orders-layout">
        <!-- Orders List -->
        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <p>No orders found.</p>
                </div>
            <?php else: ?>
                <div class="orders-table-wrapper">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td>
                                        <div class="customer-info">
                                            <strong><?php echo htmlspecialchars($order['full_name'] ?? $order['username']); ?></strong>
                                            <small><?php echo htmlspecialchars($order['email']); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo $order['item_count']; ?> item(s)</td>
                                    <td>RM <?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="admin_orders.php?id=<?php echo $order['id']; ?>" class="btn-small">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Order Details Panel -->
        <?php if ($orderDetails): ?>
            <div class="order-details-panel">
                <div class="details-header">
                    <h2>Order #<?php echo str_pad($orderDetails['id'], 6, '0', STR_PAD_LEFT); ?></h2>
                    <a href="admin_orders.php" class="close-btn">&times;</a>
                </div>
                
                <div class="details-content">
                    <div class="detail-section">
                        <h3>Customer Information</h3>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($orderDetails['full_name'] ?? $orderDetails['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($orderDetails['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($orderDetails['phone'] ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="detail-section">
                        <h3>Order Information</h3>
                        <p><strong>Date:</strong> <?php echo date('M d, Y h:i A', strtotime($orderDetails['order_date'])); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="status-badge status-<?php echo strtolower($orderDetails['status']); ?>">
                                <?php echo htmlspecialchars($orderDetails['status']); ?>
                            </span>
                        </p>
                    </div>
                    
                    <div class="detail-section">
                        <h3>Update Status</h3>
                        <form method="POST" class="status-form">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="order_id" value="<?php echo $orderDetails['id']; ?>">
                            <select name="status" class="status-select">
                                <option value="Pending" <?php echo strtolower($orderDetails['status']) === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Processing" <?php echo strtolower($orderDetails['status']) === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="Shipped" <?php echo strtolower($orderDetails['status']) === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="Delivered" <?php echo strtolower($orderDetails['status']) === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="Cancelled" <?php echo strtolower($orderDetails['status']) === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-primary">Update Status</button>
                        </form>
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
                                        <p>Unit Price: RM <?php echo number_format($item['price'], 2); ?></p>
                                    </div>
                                    <div class="item-total">
                                        <p><strong>RM <?php echo number_format($item['qty'] * $item['price'], 2); ?></strong></p>
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
</div>

<?php
include '_footer.php';
?>

