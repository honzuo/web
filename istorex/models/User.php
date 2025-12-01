<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    // 获取用户信息
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT id, username, email, full_name, phone, address, profile_image, role, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        return $user;
    }
    
    // 更新用户信息
    public function updateProfile($userId, $data) {
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET full_name = ?, phone = ?, address = ?, email = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param("ssssi", 
            $data['full_name'], 
            $data['phone'], 
            $data['address'], 
            $data['email'],
            $userId
        );
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    // 更新头像
    public function updateProfileImage($userId, $imagePath) {
        $stmt = $this->conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param("si", $imagePath, $userId);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    // 修改密码
    public function updatePassword($userId, $oldPassword, $newPassword) {
        // 先验证旧密码
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if (!$user || !password_verify($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // 更新新密码
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        $result = $stmt->execute();
        $stmt->close();
        
        return ['success' => $result, 'message' => $result ? 'Password updated successfully' : 'Failed to update password'];
    }
    
    // 用户登录验证
    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user && password_verify($password, $user['password'])) {
            return [
                'success' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
        }
        
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    // 用户注册（默认 member 角色）
    public function register($username, $email, $password, $fullName, $role = 'member') {
        // 检查用户名是否已存在
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Username or email already exists'];
        }
        $stmt->close();
        
        // 创建新用户
        if (!in_array($role, ['admin', 'member', 'staff'])) {
            $role = 'member';
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (role, username, email, password, full_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $role, $username, $email, $hashedPassword, $fullName);
        $result = $stmt->execute();
        $userId = $stmt->insert_id;
        $stmt->close();
        
        if ($result) {
            return ['success' => true, 'user_id' => $userId, 'message' => 'Registration successful'];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }

    // 获取用户列表（带基础搜索）
    public function getUsers($search = '') {
        $users = [];

        if (!empty($search)) {
            $like = '%' . $search . '%';
            $stmt = $this->conn->prepare("
                SELECT id, username, email, full_name, phone, role, created_at 
                FROM users
                WHERE username LIKE ? 
                   OR email LIKE ?
                   OR full_name LIKE ?
                ORDER BY created_at DESC
            ");
            $stmt->bind_param("sss", $like, $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query("
                SELECT id, username, email, full_name, phone, role, created_at 
                FROM users
                ORDER BY created_at DESC
            ");
        }

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        if (isset($stmt)) {
            $stmt->close();
        }

        return $users;
    }
}
?>