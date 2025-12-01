<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>iStoreX</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/navigation.css">
    <link rel="stylesheet" href="../public/css/responsive.css">
</head>

<body>
    <header>
        <a href="home.php" id="logo">iStoreX</a>
        <nav>
            <?php if ($role === 'admin'): ?>
                <a href="products.php">Products</a>
                <a href="#">Cart</a>
                <a href="order_history.php">History</a>
                <a href="admin_orders.php">List</a>
                <a href="admin_members.php">Member Management</a>
                <a href="profile.php">Profile</a>
            <?php elseif ($role === 'member'): ?>
                <a href="products.php">Products</a>
                <a href="#">Cart</a>
                <a href="order_history.php">History</a>
                <a href="profile.php">Profile</a>
            <?php elseif ($role === 'staff'): ?>
                <a href="products.php">Products</a>
                <a href="#">Cart</a>
                <a href="order_history.php">History</a>
                <a href="admin_orders.php">List</a>
                <a href="profile.php">Profile</a>
            <?php else: ?>
                <!-- Guest (not logged in) -->
                <a href="products.php">Products</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>