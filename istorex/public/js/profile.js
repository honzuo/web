// Profile JavaScript Functions

// 等待 DOM 加载完成
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile JS loaded!');
    
    // DOM元素
    const profileNavLinks = document.querySelectorAll('.profile-nav-link');
    const profileTabs = document.querySelectorAll('.profile-tab');
    const avatarUpload = document.getElementById('avatarUpload');
    const avatarForm = document.getElementById('avatarForm');
    const profileAvatarPreview = document.getElementById('profileAvatarPreview');
    const alertMessage = document.getElementById('alertMessage');
    
    console.log('Found nav links:', profileNavLinks.length);
    console.log('Found tabs:', profileTabs.length);
    
    // 标签切换功能
    profileNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const tabName = this.getAttribute('data-tab');
            
            console.log('Clicked tab:', tabName);
            
            if (!tabName) return; // 如果没有data-tab属性（比如logout链接），直接返回
            
            e.preventDefault();
            
            // 移除所有活动状态
            profileNavLinks.forEach(l => l.classList.remove('active'));
            profileTabs.forEach(t => t.classList.remove('active'));
            
            // 添加活动状态
            this.classList.add('active');
            const targetTab = document.getElementById(`tab-${tabName}`);
            if (targetTab) {
                targetTab.classList.add('active');
                console.log('Activated tab:', tabName);
            } else {
                console.error('Tab not found:', `tab-${tabName}`);
            }
        });
    });
    
    // 头像上传功能
    if (avatarUpload) {
        avatarUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // 验证文件类型
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    showAlert('Invalid file type. Only JPG, PNG, and GIF are allowed.', 'error');
                    return;
                }
                
                // 验证文件大小 (5MB)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    showAlert('File too large. Maximum size is 5MB.', 'error');
                    return;
                }
                
                // 预览图片
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileAvatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // 自动提交表单
                if (confirm('Do you want to upload this image?')) {
                    avatarForm.submit();
                }
            }
        });
    }
    
    // 密码确认验证
    const passwordForms = document.querySelectorAll('form');
    passwordForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = this.querySelector('input[name="action"]');
            if (action && action.value === 'update_password') {
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    showAlert('New passwords do not match!', 'error');
                    return false;
                }
                
                if (newPassword.length < 6) {
                    e.preventDefault();
                    showAlert('Password must be at least 6 characters long!', 'error');
                    return false;
                }
            }
            
            if (action && action.value === 'update_profile') {
                const email = document.getElementById('email').value;
                
                // 简单的邮箱验证
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showAlert('Please enter a valid email address!', 'error');
                    return false;
                }
            }
        });
    });
    
    // 显示提示消息
    function showAlert(message, type) {
        // 移除旧的提示
        const oldAlert = document.querySelector('.alert');
        if (oldAlert) {
            oldAlert.remove();
        }
        
        // 创建新的提示
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.id = 'alertMessage';
        
        const container = document.querySelector('.profile-container');
        container.insertBefore(alert, container.firstChild);
        
        // 3秒后自动隐藏
        setTimeout(() => {
            alert.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }
    
    // 自动隐藏提示消息
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => alertMessage.remove(), 300);
        }, 5000);
    }
    
    // 电话号码格式化（马来西亚格式）
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // 马来西亚电话格式: +60 12-345 6789
            if (value.startsWith('60')) {
                value = value.slice(2);
            }
            
            if (value.length > 0) {
                if (value.length <= 2) {
                    e.target.value = '+60 ' + value;
                } else if (value.length <= 5) {
                    e.target.value = '+60 ' + value.slice(0, 2) + '-' + value.slice(2);
                } else {
                    e.target.value = '+60 ' + value.slice(0, 2) + '-' + value.slice(2, 5) + ' ' + value.slice(5, 9);
                }
            }
        });
    }
    
    // 图片加载错误处理
    if (profileAvatarPreview) {
        profileAvatarPreview.addEventListener('error', function() {
            this.src = '../public/images/avatars/default-avatar.png';
        });
    }
    
    console.log('Profile page initialized successfully!');
});

// 确认删除账户（预留功能）
function confirmDeleteAccount() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone!')) {
        if (confirm('Please confirm again. All your data will be permanently deleted!')) {
            // 这里可以添加删除账户的逻辑
            window.location.href = 'delete_account.php';
        }
    }
}