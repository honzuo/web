<?php
// controllers/UserController.php - DEBUG VERSION
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
                        $user = $this->userModel->getUserById($userId);
                        break;
                }
            }
        }

        require_once __DIR__ . '/../views/profile.php';
    }

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

    private function updateAvatar()
    {
        $userId = $_SESSION['user_id'];

        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'No file uploaded or upload error'];
        }

        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File too large. Maximum size is 5MB'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = __DIR__ . '/../public/images/avatars/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
            if ($this->userModel->updateProfileImage($userId, $filename)) {
                return ['success' => true, 'message' => 'Profile image updated successfully'];
            }
        }

        return ['success' => false, 'message' => 'Failed to upload image'];
    }
    
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

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['full_name'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

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

    // ==================== 密码重置功能 ====================
    
    public function forgotPassword()
    {
        echo "<!-- DEBUG: forgotPassword() started -->\n";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<!-- DEBUG: POST request received -->\n";
            
            $email = $_POST['email'] ?? '';
            echo "<!-- DEBUG: Email = " . htmlspecialchars($email) . " -->\n";

            if (empty($email)) {
                $error = 'Email is required';
                echo "<!-- DEBUG: Email is empty -->\n";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format';
                echo "<!-- DEBUG: Invalid email format -->\n";
            } else {
                echo "<!-- DEBUG: Checking if user exists -->\n";
                
                try {
                    $user = $this->userModel->getUserByEmail($email);
                    echo "<!-- DEBUG: getUserByEmail completed -->\n";
                    
                    if (!$user) {
                        $error = 'No account found with this email address';
                        echo "<!-- DEBUG: User not found -->\n";
                    } else {
                        echo "<!-- DEBUG: User found: " . htmlspecialchars($user['username']) . " -->\n";
                        
                        // 生成OTP
                        $otp = sprintf("%06d", mt_rand(0, 999999));
                        echo "<!-- DEBUG: OTP generated: " . $otp . " -->\n";
                        
                        // 保存OTP
                        echo "<!-- DEBUG: Saving OTP to database -->\n";
                        $result = $this->userModel->saveOTP($email, $otp);
                        echo "<!-- DEBUG: saveOTP result: " . ($result ? 'true' : 'false') . " -->\n";
                        
                        if ($result) {
                            // 发送邮件
                            echo "<!-- DEBUG: Attempting to send email -->\n";
                            $emailSent = $this->sendOTPEmail($email, $otp, $user['full_name']);
                            echo "<!-- DEBUG: Email sent result: " . ($emailSent ? 'true' : 'false') . " -->\n";
                            
                            if ($emailSent) {
                                $_SESSION['reset_email'] = $email;
                                echo "<!-- DEBUG: Session saved, redirecting to verify_otp.php -->\n";
                                header('Location: verify_otp.php');
                                exit();
                            } else {
                                $error = 'Failed to send email. Please try again later.';
                                echo "<!-- DEBUG: Email sending failed -->\n";
                            }
                        } else {
                            $error = 'Failed to generate OTP. Please try again.';
                            echo "<!-- DEBUG: OTP save failed -->\n";
                        }
                    }
                } catch (Exception $e) {
                    echo "<!-- DEBUG ERROR: " . $e->getMessage() . " -->\n";
                    echo "<!-- DEBUG ERROR FILE: " . $e->getFile() . " -->\n";
                    echo "<!-- DEBUG ERROR LINE: " . $e->getLine() . " -->\n";
                    $error = 'An error occurred: ' . $e->getMessage();
                }
            }
        }

        echo "<!-- DEBUG: Loading view -->\n";
        require_once __DIR__ . '/../views/forgot_password.php';
        echo "<!-- DEBUG: View loaded -->\n";
    }

    public function verifyOTP()
    {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: forgot_password.php');
            exit();
        }

        $email = $_SESSION['reset_email'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
            $user = $this->userModel->getUserByEmail($email);
            $otp = sprintf("%06d", mt_rand(0, 999999));
            
            $this->userModel->saveOTP($email, $otp);
            $this->sendOTPEmail($email, $otp, $user['full_name']);
            
            $success = 'New OTP code has been sent to your email';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
            $otp = $_POST['otp'] ?? '';

            if (empty($otp)) {
                $error = 'Please enter the OTP code';
            } elseif (strlen($otp) !== 6) {
                $error = 'OTP must be 6 digits';
            } else {
                $result = $this->userModel->verifyOTP($email, $otp);
                
                if ($result['success']) {
                    $_SESSION['otp_verified'] = true;
                    header('Location: reset_password.php');
                    exit();
                } else {
                    $error = $result['message'];
                }
            }
        }

        require_once __DIR__ . '/../views/verify_otp.php';
    }

    public function resetPassword()
    {
        if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_email'])) {
            header('Location: forgot_password.php');
            exit();
        }

        $email = $_SESSION['reset_email'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($newPassword) || empty($confirmPassword)) {
                $error = 'All fields are required';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Passwords do not match';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password must be at least 6 characters long';
            } else {
                $result = $this->userModel->resetPassword($email, $newPassword);
                
                if ($result['success']) {
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['otp_verified']);
                    
                    $success = 'Password reset successfully! Redirecting to login...';
                } else {
                    $error = $result['message'];
                }
            }
        }

        require_once __DIR__ . '/../views/reset_password.php';
    }

private function sendOTPEmail($to, $otp, $name)
{
    echo "<!-- DEBUG sendOTPEmail: TO=" . htmlspecialchars($to) . ", OTP=" . $otp . " -->\n";
    
    // 引入 PHPMailer
    require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer/SMTP.php';
    require_once __DIR__ . '/../PHPMailer/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // 服务器设置
        $mail->SMTPDebug = 0;                      // 0=关闭调试, 2=显示详细信息
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gansq-wm23@student.tarc.edu.my';
        $mail->Password   = 'kmziylissuqjtcwr';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        
        // 收件人
        $mail->setFrom('gansq-wm23@student.tarc.edu.my', 'iStoreX');
        $mail->addAddress($to, $name ?: 'User');
        
        // 内容
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Password Reset OTP - iStoreX';
        
        if (empty($name)) {
            $name = 'User';
        }
        
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .otp-box { background: #f0f4ff; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0; border-radius: 10px; }
                .otp-code { font-size: 36px; font-weight: bold; color: #667eea; letter-spacing: 8px; margin: 10px 0; }
                .info { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>iStoreX</h1>
                    <p style='margin: 5px 0 0 0;'>Password Reset Request</p>
                </div>
                <div class='content'>
                    <p style='font-size: 16px;'>Hi <strong>" . htmlspecialchars($name) . "</strong>,</p>
                    <p>We received a request to reset your password for your iStoreX account. Use the OTP code below to proceed:</p>
                    
                    <div class='otp-box'>
                        <p style='margin: 0; color: #666; font-size: 14px;'>Your OTP Code</p>
                        <p class='otp-code'>" . $otp . "</p>
                        <p style='margin: 0; color: #e74c3c; font-size: 14px; font-weight: bold;'>Valid for 10 minutes</p>
                    </div>
                    
                    <div class='info'>
                        <p style='margin: 0; font-weight: bold;'>Important Security Information:</p>
                        <ul style='margin: 10px 0 0 20px; padding: 0;'>
                            <li>This code will expire in 10 minutes</li>
                            <li>Don't share this code with anyone</li>
                            <li>iStoreX will never ask for your OTP via phone or email</li>
                            <li>If you didn't request this, please ignore this email and secure your account</li>
                        </ul>
                    </div>
                    
                    <p style='margin-top: 30px;'>Best regards,<br><strong>The iStoreX Team</strong></p>
                </div>
                <div class='footer'>
                    <p>This is an automated email. Please do not reply.</p>
                    <p>© 2025 iStoreX. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $mail->send();
        echo "<!-- DEBUG: PHPMailer - Email sent successfully! -->\n";
        return true;
        
    } catch (Exception $e) {
        echo "<!-- DEBUG: PHPMailer Error: {$mail->ErrorInfo} -->\n";
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

    public function logout()
    {
        session_destroy();
        header('Location: login.php');
        exit();
    }
}