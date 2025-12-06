<?php
// views/_header.php
// 开启 Session 以便检查登录状态
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iStoreX</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/navigation.css">
    <link rel="stylesheet" href="../public/css/responsive.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <header>
        <div class="header-content">
            <a href="home.php" id="logo">iStoreX</a>

            <nav>
                <a href="home.php">Home</a>
                <a href="products.php">Products</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="cart.php">Cart 🛒</a>
                    <a href="profile.php">Profile</a>
                    <a href="logout.php" style="color: #ff6b6b;">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <script>
        var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>