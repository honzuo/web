<?php
// 引入 HTML Helper
require_once __DIR__ . '/../lib/HtmlHelper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - iStoreX</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* 保持你原本的 CSS 样式不变，为了节省篇幅我省略了 style 标签内的内容，请把之前的 CSS 复制回来 */
        /* ... CSS 代码 ... */
        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <h1>iStoreX</h1>
                <p>Login to your account</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form" id="loginForm">
                <div class="form-group">
                    <label>Username</label>
                    <?php echo Html::input('text', 'username', '', [
                        'placeholder' => 'Username or Email',
                        'id' => 'username',
                        'class' => 'form-control',
                        'autofocus' => 'autofocus'
                    ]); ?>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <?php echo Html::input('password', 'password', '', [
                        'placeholder' => 'Password',
                        'id' => 'password',
                        'class' => 'form-control'
                    ]); ?>
                </div>

                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>

                <?php echo Html::button('Login', 'submit', ['class' => 'btn-submit']); ?>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p><a href="home.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                // 获取值
                var username = $('#username').val().trim();
                var password = $('#password').val();

                // 简单的客户端验证
                if (username === '' || password === '') {
                    e.preventDefault(); // 阻止表单提交
                    alert('Please fill in both username and password.');
                    return false;
                }
            });

            // 聚焦时移除错误样式 (可选)
            $('input').on('focus', function() {
                $(this).css('border', '1px solid #667eea');
            });
        });
    </script>
</body>

</html>