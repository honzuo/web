<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        // 启动 session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }

    // 显示个人资料页面
    public function profile()
    {
        // 检查用户是否登录
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        // 处理表单提交
        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'update_profile':
                        $message = $this->updateProfile();
                        $messageType = 'success';
                        // 重新获取用户数据
                        $user = $this->userModel->getUserById($userId);
                        break;

                    case 'update_password':
                        $result = $this->updatePassword();
                        $message = $result['message'];
                        $messageType = $result['success'] ? 'success' : 'error';
                        break;

                    case 'update_avatar':
                        $result = $this->updateAvatar();
                        $message = $result['message'];
                        $messageType = $result['success'] ? 'success' : 'error';
                        // 重新获取用户数据
                        $user = $this->userModel->getUserById($userId);
                        break;
                }
            }
        }

        require_once __DIR__ . '/../views/profile.php';
    }

    // 更新个人资料
    private function updateProfile()
    {
        $userId = $_SESSION['user_id'];

        $data = [
            'full_name' => $_POST['full_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];

        if ($this->userModel->updateProfile($userId, $data)) {
            return 'Profile updated successfully!';
        }

        return 'Failed to update profile';
    }

    // 更新密码
    private function updatePassword()
    {
        $userId = $_SESSION['user_id'];
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            return ['success' => false, 'message' => 'All password fields are required'];
        }

        if ($newPassword !== $confirmPassword) {
            return ['success' => false, 'message' => 'New passwords do not match'];
        }

        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters long'];
        }

        return $this->userModel->updatePassword($userId, $oldPassword, $newPassword);
    }

    // 更新头像
    private function updateAvatar()
    {
        $userId = $_SESSION['user_id'];

        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'No file uploaded or upload error'];
        }

        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // 验证文件类型
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed'];
        }

        // 验证文件大小
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File too large. Maximum size is 5MB'];
        }

        // 生成唯一文件名
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;

        // 修正上传路径
        $uploadPath = __DIR__ . '/../public/images/avatars/';

        // 创建目录(如果不存在)
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // 移动文件
        if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
            if ($this->userModel->updateProfileImage($userId, $filename)) {
                return ['success' => true, 'message' => 'Profile image updated successfully'];
            }
        }

        return ['success' => false, 'message' => 'Failed to upload image'];
    }
    // 登录
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->userModel->login($username, $password);

            if ($result['success']) {
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['username'] = $result['username'];
                header('Location: profile.php');
                exit();
            }

            $error = $result['message'];
        }

        require_once __DIR__ . '/../views/login.php';
    }

    // 注册
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['full_name'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // 验证输入
            if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
                $error = 'All fields are required';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters long';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format';
            } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
                $error = 'Username must be 3-20 characters, letters, numbers, and underscores only';
            } else {
                $result = $this->userModel->register($username, $email, $password, $fullName);

                if ($result['success']) {
                    // 注册成功，自动登录
                    $_SESSION['user_id'] = $result['user_id'];
                    $_SESSION['username'] = $username;
                    header('Location: profile.php');
                    exit();
                } else {
                    $error = $result['message'];
                }
            }
        }

        require_once __DIR__ . '/../views/register.php';
    }

    // 登出
    public function logout()
    {
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
