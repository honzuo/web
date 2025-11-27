<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - iStoreX</title>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .login-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo h1 {
            color: #333;
            font-size: 32px;
            margin: 0;
        }
        
        .login-logo p {
            color: #666;
            margin: 5px 0 0 0;
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
        }
        
        .login-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }
        
        .login-form input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .login-form button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .login-form button:hover {
            transform: translateY(-2px);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
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
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <input type="text" 
                           name="username" 
                           placeholder="Username or Email" 
                           required 
                           autofocus>
                </div>
                
                <div class="form-group">
                    <input type="password" 
                           name="password" 
                           placeholder="Password" 
                           required>
                </div>
                
                <button type="submit">Login</button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p><a href="home.php">Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>