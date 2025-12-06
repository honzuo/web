<?php
// config/database.php
// 严格遵守规则：使用 PDO 进行数据库连接 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "istorex";

try {
    // 创建 PDO 实例
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // 设置错误模式为异常 (Exception)，这对调试非常重要
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 设置默认获取模式为关联数组 (Associative Array)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // 生产环境中不要直接输出错误信息给用户，但开发时可以
    die("Database Connection Failed: " . $e->getMessage());
}
?>