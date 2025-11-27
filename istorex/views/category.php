<?php
include '_header.php';
?>

<link rel="stylesheet" href="../public/css/categories.css">

<div class="container">
    <?php if ($category): ?>
        <div class="breadcrumb">
            <a href="home.php">Home</a> / <span><?php echo $category['name']; ?></span>
        </div>
        
        <h2><?php echo $category['name']; ?></h2>
        
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
                <p>No products available in this category</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Category not found</p>
    <?php endif; ?>
</div>

<?php
include '_footer.php';
?>