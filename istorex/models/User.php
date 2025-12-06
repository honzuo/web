<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        global $conn; // 使用 config/database.php 里创建的 PDO 对象
        $this->conn = $conn;
    }

    // 获取用户信息
    public function getUserById($userId)
    {
        // PDO 写法：使用 :id 占位符
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(); // 获取单行数据
    }

    // 根据邮箱获取用户
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // 用户登录验证
    public function login($username, $password)
    {
        $sql = "SELECT id, username, password, role FROM users WHERE username = :u OR email = :e";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':u' => $username, ':e' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return [
                'success' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'] ?? 'member'
            ];
        }

        return ['success' => false, 'message' => 'Invalid username or password'];
    }

    // 用户注册
    public function register($username, $email, $password, $fullName)
    {
        // 1. 检查是否存在
        $checkSql = "SELECT COUNT(*) FROM users WHERE username = :u OR email = :e";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->execute([':u' => $username, ':e' => $email]);

        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }

        // 2. 插入数据
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'member';

        $sql = "INSERT INTO users (role, username, email, password, full_name, created_at) VALUES (:role, :u, :e, :p, :f, NOW())";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ':role' => $role,
                ':u' => $username,
                ':e' => $email,
                ':p' => $hashedPassword,
                ':f' => $fullName
            ]);
            return ['success' => true, 'user_id' => $this->conn->lastInsertId(), 'message' => 'Registration successful'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'DB Error: ' . $e->getMessage()];
        }
    }

    // 更新资料
    public function updateProfile($userId, $data)
    {
        $sql = "UPDATE users SET full_name = :n, phone = :p, address = :a, email = :e WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':n' => $data['full_name'],
            ':p' => $data['phone'],
            ':a' => $data['address'],
            ':e' => $data['email'],
            ':id' => $userId
        ]);
    }

    // 更新头像
    public function updateProfileImage($userId, $imagePath)
    {
        $stmt = $this->conn->prepare("UPDATE users SET profile_image = :img WHERE id = :id");
        return $stmt->execute([':img' => $imagePath, ':id' => $userId]);
    }

    // 修改密码
    public function updatePassword($userId, $oldPassword, $newPassword)
    {
        // 1. 验证旧密码
        $current = $this->getUserById($userId);
        if (!$current || !password_verify($oldPassword, $current['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // 2. 更新新密码
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = :p WHERE id = :id");
        $result = $stmt->execute([':p' => $hashed, ':id' => $userId]);

        return ['success' => $result, 'message' => $result ? 'Password updated' : 'Failed to update'];
    }

    // OTP 相关功能保持逻辑不变，只需改写为 PDO 语法即可，如果需要我也可以提供
}
