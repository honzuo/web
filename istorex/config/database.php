<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "istorex";

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 检查连接
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
