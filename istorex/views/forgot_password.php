<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - iStoreX</title>
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
        
        .forgot-container {
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
        
        .email-icon {
            text-align: center;
            font-size: 64px;
            margin-bottom: 20px;
            animation: bounce 1s ease infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .forgot-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .forgot-header p {
            color: #666;
            font-size: 15px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #e7f3ff 0%, #f0f9ff 100%);
            color: #004085;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #0066cc;
            font-size: 14px;
            line-height: 1.6;
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
        
        .form-group input {
            width: 100%;
            padding: 15px 18px;
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
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .footer-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #5568d3;
            text-decoration: underline;
        }
        
        .divider {
            color: #ccc;
            margin: 0 5px;
        }
        
        @media (max-width: 576px) {
            .forgot-container {
                padding: 40px 30px;
            }
            
            .forgot-header h1 {
                font-size: 26px;
            }
            
            .email-icon {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="email-icon">📧</div>
        
        <div class="forgot-header">
            <h1>Forgot Password?</h1>
            <p>Enter your email to receive a verification code</p>
        </div>
        
        <div class="info-box">
            💡 We'll send a 6-digit verification code to your email address. Please check your inbox and spam folder.
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
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" 
                       id="email"
                       name="email" 
                       placeholder="Enter your registered email" 
                       required 
                       autofocus
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <button type="submit" class="btn-submit">Send Verification Code</button>
        </form>
        
        <div class="footer-links">
            <a href="login.php">← Back to Login</a>
            <span class="divider">|</span>
            <a href="register.php">Create Account</a>
        </div>
    </div>
</body>
</html>