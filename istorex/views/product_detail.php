<?php
include '_header.php';
?>

<div class="container">
    <?php if ($product): ?>
        <div class="breadcrumb">
            <a href="home.php">Home</a> / 
            <a href="category.php?id=<?php echo $product['category_id']; ?>">
                <?php echo $product['category_name']; ?>
            </a> / 
            <span><?php echo $product['name']; ?></span>
        </div>
        
        <div class="product-detail">
            <div class="product-image">
                <img src="../public/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            
            <div class="product-info">
                <h1><?php echo $product['name']; ?></h1>
                <p class="product-price">RM <?php echo $product['price']; ?></p>
                
                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo $product['description']; ?></p>
                </div>
                
                <div class="product-specs">
                    <h3>Specifications</h3>
                    <ul>
                        <?php if (!empty($product['specs'])): ?>
                            <?php foreach($product['specs'] as $spec): ?>
                                <li><strong><?php echo $spec['name']; ?>:</strong> <?php echo $spec['value']; ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="product-actions">
                    <button class="btn-primary" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                    <button class="btn-secondary" onclick="buyNow(<?php echo $product['id']; ?>)">Buy Now</button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Product not found</p>
    <?php endif; ?>
</div>

<footer>
    &copy; 2025 iStoreX. All rights reserved.
</footer>

<script src="../public/js/script.js"></script>
</body>
</html>

<?php
include '_footer.php';
?>