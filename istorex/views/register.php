<?php
// 引入 HTML Helper
require_once __DIR__ . '/../lib/HtmlHelper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - iStoreX</title>
    <link rel="stylesheet" href="../public/css/profile.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* 这里保留你原本的 CSS 样式，请把原本的 style 标签内容复制回来 */
        /* 为了演示，我只保留关键的样式 */
        .register-container {
            display: flex;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .register-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 450px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .password-strength-bar {
            height: 4px;
            background: #e0e0e0;
            margin-top: 5px;
            transition: width 0.3s;
        }

        .weak {
            width: 30%;
            background: #ff4444;
        }

        .medium {
            width: 60%;
            background: #ffbb33;
        }

        .strong {
            width: 100%;
            background: #00C851;
        }

        .error-message {
            color: red;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <div class="register-logo" style="text-align: center; margin-bottom: 30px;">
                <h1>iStoreX</h1>
                <p>Create your account</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="register-form" id="registerForm">
                <div class="form-group">
                    <label>Full Name *</label>
                    <?php echo Html::input('text', 'full_name', '', [
                        'placeholder' => 'Enter your full name',
                        'required' => 'required',
                        'id' => 'full_name'
                    ]); ?>
                </div>

                <div class="form-group">
                    <label>Username *</label>
                    <?php echo Html::input('text', 'username', '', [
                        'placeholder' => 'Choose a username',
                        'required' => 'required',
                        'id' => 'username',
                        // pattern 属性也可以放在 input helper 里
                        'pattern' => '[a-zA-Z0-9_]{3,20}',
                        'title' => '3-20 characters, letters, numbers, and underscores only'
                    ]); ?>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <?php echo Html::input('email', 'email', '', [
                        'placeholder' => 'your.email@example.com',
                        'required' => 'required',
                        'id' => 'email'
                    ]); ?>
                </div>

                <div class="form-group">
                    <label>Password *</label>
                    <?php echo Html::input('password', 'password', '', [
                        'placeholder' => 'Create a strong password',
                        'required' => 'required',
                        'id' => 'password',
                        'minlength' => '6'
                    ]); ?>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm Password *</label>
                    <?php echo Html::input('password', 'confirm_password', '', [
                        'placeholder' => 'Re-enter your password',
                        'required' => 'required',
                        'id' => 'confirm_password'
                    ]); ?>
                </div>

                <?php echo Html::button('Create Account', 'submit'); ?>
            </form>

            <div class="register-footer" style="text-align: center; margin-top: 20px;">
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <p><a href="home.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // 1. 密码强度实时检测
            $('#password').on('input', function() {
                var password = $(this).val();
                var strength = 0;
                var bar = $('#passwordStrengthBar');

                // 移除所有类
                bar.removeClass('weak medium strong');

                if (password.length > 0) {
                    if (password.length >= 6) strength++;
                    if (password.length >= 10) strength++;
                    if (/[A-Z]/.test(password)) strength++;
                    if (/[0-9]/.test(password)) strength++;

                    if (strength < 2) {
                        bar.addClass('weak');
                    } else if (strength < 4) {
                        bar.addClass('medium');
                    } else {
                        bar.addClass('strong');
                    }
                }
            });

            // 2. 表单提交验证
            $('#registerForm').on('submit', function(e) {
                var pass = $('#password').val();
                var confirm = $('#confirm_password').val();

                if (pass !== confirm) {
                    e.preventDefault(); // 阻止提交
                    alert('Passwords do not match!');
                    $('#confirm_password').css('border-color', 'red');
                } else if (pass.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters!');
                }
            });

            // 输入时重置边框颜色
            $('input').on('input', function() {
                $(this).css('border-color', '#ddd');
            });
        });
    </script>
</body>

</html>