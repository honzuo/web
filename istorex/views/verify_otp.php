<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - iStoreX</title>
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
        
        .verify-container {
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
        
        .lock-icon {
            text-align: center;
            font-size: 64px;
            margin-bottom: 20px;
            animation: pulse 2s ease infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .verify-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .verify-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .verify-header p {
            color: #666;
            font-size: 15px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #e7f3ff 0%, #f0f9ff 100%);
            color: #004085;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            border: 2px dashed #0066cc;
        }
        
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .info-box strong {
            color: #667eea;
            font-size: 16px;
            display: block;
            margin-top: 8px;
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
        
        .otp-label {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }
        
        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 25px;
        }
        
        .otp-input {
            width: 55px;
            height: 60px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            transition: all 0.3s;
            color: #667eea;
        }
        
        .otp-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: scale(1.05);
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
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .resend-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .resend-section p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .btn-resend {
            background: none;
            border: none;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
            transition: color 0.3s;
        }
        
        .btn-resend:hover {
            color: #5568d3;
        }
        
        .btn-resend:disabled {
            color: #999;
            cursor: not-allowed;
            text-decoration: none;
        }
        
        .timer {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .footer-links {
            text-align: center;
            margin-top: 20px;
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
            .verify-container {
                padding: 40px 25px;
            }
            
            .verify-header h1 {
                font-size: 26px;
            }
            
            .otp-inputs {
                gap: 8px;
            }
            
            .otp-input {
                width: 45px;
                height: 50px;
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="lock-icon">🔐</div>
        
        <div class="verify-header">
            <h1>Verify OTP</h1>
            <p>Enter the 6-digit code we sent</p>
        </div>
        
        <div class="info-box">
            <p>📨 We've sent a verification code to</p>
            <strong><?php echo htmlspecialchars($email ?? ''); ?></strong>
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
        
        <form method="POST" id="otpForm">
            <div class="otp-label">Enter OTP Code</div>
            <div class="otp-inputs">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
            </div>
            <input type="hidden" name="otp" id="otpValue">
            
            <button type="submit" class="btn-submit">Verify Code</button>
        </form>
        
        <div class="resend-section">
            <p>Didn't receive the code?</p>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="resend" value="1">
                <button type="submit" class="btn-resend" id="resendBtn">
                    Resend Code <span class="timer" id="timer"></span>
                </button>
            </form>
        </div>
        
        <div class="footer-links">
            <a href="forgot_password.php">← Use different email</a>
        </div>
    </div>
    
    <script>
        // OTP输入框处理
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpForm = document.getElementById('otpForm');
        const otpValue = document.getElementById('otpValue');
        
        otpInputs.forEach((input, index) => {
            // 输入处理
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                updateOTPValue();
            });
            
            // 退格键处理
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            // 粘贴处理
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                
                for (let i = 0; i < pastedData.length && index + i < otpInputs.length; i++) {
                    otpInputs[index + i].value = pastedData[i];
                }
                
                updateOTPValue();
                
                const lastIndex = Math.min(index + pastedData.length - 1, otpInputs.length - 1);
                otpInputs[lastIndex].focus();
            });
        });
        
        function updateOTPValue() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpValue.value = otp;
        }
        
        // 表单验证
        otpForm.addEventListener('submit', function(e) {
            updateOTPValue();
            if (otpValue.value.length !== 6) {
                e.preventDefault();
                alert('⚠️ Please enter all 6 digits');
                otpInputs[0].focus();
            }
        });
        
        // 重发倒计时
        const resendBtn = document.getElementById('resendBtn');
        const timer = document.getElementById('timer');
        let countdown = 60;
        
        function startTimer() {
            resendBtn.disabled = true;
            const interval = setInterval(() => {
                countdown--;
                timer.textContent = `(${countdown}s)`;
                
                if (countdown <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    timer.textContent = '';
                    countdown = 60;
                }
            }, 1000);
        }
        
        // 页面加载时启动倒计时
        startTimer();
    </script>
</body>
</html>