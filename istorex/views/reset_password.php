<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - iStoreX</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .reset-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px 40px;
            width: 100%;
            max-width: 480px;
            animation: slideIn 0.4s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .key-icon {
            text-align: center;
            font-size: 64px;
            margin-bottom: 20px;
            animation: rotate 3s ease infinite;
        }
        
        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        
        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .reset-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .reset-header p {
            color: #666;
            font-size: 15px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            animation: shake 0.4s ease;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 50px 15px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 22px;
            user-select: none;
            transition: transform 0.2s;
        }
        
        .toggle-password:hover {
            transform: translateY(-50%) scale(1.1);
        }
        
        .password-strength {
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 2px;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
            border-radius: 2px;
        }
        
        .password-strength-bar.weak {
            width: 33%;
            background-color: #f44336;
        }
        
        .password-strength-bar.medium {
            width: 66%;
            background-color: #ff9800;
        }
        
        .password-strength-bar.strong {
            width: 100%;
            background-color: #4caf50;
        }
        
        .form-text {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #666;
        }
        
        .password-match {
            font-size: 13px;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .password-match.match {
            color: #4caf50;
        }
        
        .password-match.no-match {
            color: #f44336;
        }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .footer-links {
            text-align: center;
            margin-top: 25px;
        }
        
        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #5568d3;
            text-decoration: underline;
        }
        
        @media (max-width: 576px) {
            .reset-container {
                padding: 40px 30px;
            }
            
            .reset-header h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="key-icon">🔑</div>
        
        <div class="reset-header">
            <h1>Reset Password</h1>
            <p>Create your new secure password</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
            </script>
        <?php endif; ?>
        
        <form method="POST" id="resetForm">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="password-wrapper">
                    <input type="password" 
                           id="new_password"
                           name="new_password" 
                           placeholder="Enter new password" 
                           required
                           minlength="6">
                    <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <small class="form-text">Minimum 6 characters, include letters and numbers for better security</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="password-wrapper">
                    <input type="password" 
                           id="confirm_password"
                           name="confirm_password" 
                           placeholder="Re-enter new password" 
                           required
                           minlength="6">
                    <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                </div>
                <div class="password-match" id="matchIndicator"></div>
            </div>
            
            <button type="submit" class="btn-submit">Reset Password</button>
        </form>
        
        <div class="footer-links">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>
    
    <script>
        // 切换密码可见性
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
        
        // 密码强度检测
        const passwordInput = document.getElementById('new_password');
        const strengthBar = document.getElementById('strengthBar');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('weak');
            } else if (strength <= 4) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
            
            checkPasswordMatch();
        });
        
        // 密码匹配检测
        const confirmInput = document.getElementById('confirm_password');
        const matchIndicator = document.getElementById('matchIndicator');
        
        confirmInput.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            
            if (confirm.length === 0) {
                matchIndicator.textContent = '';
                matchIndicator.className = 'password-match';
                return;
            }
            
            if (password === confirm) {
                matchIndicator.textContent = '✓ Passwords match';
                matchIndicator.className = 'password-match match';
            } else {
                matchIndicator.textContent = '✗ Passwords do not match';
                matchIndicator.className = 'password-match no-match';
            }
        }
        
        // 表单验证
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('⚠️ Passwords do not match!');
                confirmInput.focus();
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('⚠️ Password must be at least 6 characters long!');
                passwordInput.focus();
                return false;
            }
        });
    </script>
</body>
</html>