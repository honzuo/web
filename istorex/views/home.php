<?php
include '_header.php';
?>

<div class="banner">
    <img src="../public/images/banner/banner1.jpg" class="banner-slide" alt="Banner 1">
    <img src="../public/images/banner/banner2.jpg" class="banner-slide" alt="Banner 2">
    <img src="../public/images/banner/banner3.jpg" class="banner-slide" alt="Banner 3">
    <img src="../public/images/banner/banner4.jpg" class="banner-slide" alt="Banner 4">
    <img src="../public/images/banner/banner5.jpg" class="banner-slide" alt="Banner 5">
</div>

<div class="container">
    <!-- Categories Section -->
    <h2>Shop by Category</h2>
    <div class="categories">
        <?php if (!empty($categories)): ?>
            <?php foreach($categories as $cat): ?>
                <a href="category.php?id=<?php echo $cat['id']; ?>" class="category-card">
                    <img src="../public/images/categories/<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>">
                    <h3><?php echo $cat['name']; ?></h3>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No categories available</p>
        <?php endif; ?>
    </div>

    <!-- Hot Products Section -->
    <h2>Hot Products</h2>
    <div class="products">
        <?php if (!empty($products)): ?>
            <?php foreach($products as $p): ?>
                <a href="product_detail.php?id=<?php echo $p['id']; ?>" class="product-card">
                    <img src="../public/images/<?php echo $p['image']; ?>" alt="<?php echo $p['name']; ?>">
                    <h3><?php echo $p['name']; ?></h3>
                    <p>RM <?php echo $p['price']; ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available</p>
        <?php endif; ?>
    </div>
</div>

<?php
include '_footer.php';
?>