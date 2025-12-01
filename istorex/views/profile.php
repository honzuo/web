<?php
include '_header.php';
?>

<div class="container profile-container">
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>" id="alertMessage">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="profile-layout">
        <!-- 侧边栏 -->
        <div class="profile-sidebar">
            <div class="profile-avatar-section">
                <div class="avatar-wrapper">
                    <img src="../public/images/avatars/<?php echo htmlspecialchars($user['profile_image']); ?>" 
                         alt="Profile Image" 
                         id="profileAvatarPreview">
                    <div class="avatar-overlay">
                        <label for="avatarUpload" class="avatar-upload-label">
                            <span>Change Photo</span>
                        </label>
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data" id="avatarForm">
                    <input type="hidden" name="action" value="update_avatar">
                    <input type="file" 
                           id="avatarUpload" 
                           name="profile_image" 
                           accept="image/*" 
                           style="display: none;">
                </form>
            </div>
            
            <div class="profile-info-quick">
                <h2><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></h2>
                <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></p>
                <p class="text-muted">Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
            </div>
            
            <nav class="profile-nav">
                <a href="#" class="profile-nav-link active" data-tab="info">
                    <span>📋</span> Personal Info
                </a>
                <a href="#" class="profile-nav-link" data-tab="security">
                    <span>🔒</span> Security
                </a>
                <a href="logout.php" class="profile-nav-link">
                    <span>🚪</span> Logout
                </a>
            </nav>
        </div>
        
        <!-- 主内容区 -->
        <div class="profile-main">
            <!-- 个人信息标签 -->
            <div class="profile-tab active" id="tab-info">
                <h3>Personal Information</h3>
                <form method="POST" class="profile-form">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" 
                               id="full_name" 
                               name="full_name" 
                               value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" 
                               placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" 
                               id="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" 
                               disabled>
                        <small class="form-text">Username cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                               placeholder="+60 12-345 6789">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="4" 
                                  placeholder="Enter your address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>
            
            <!-- 安全设置标签 -->
            <div class="profile-tab" id="tab-security">
                <h3>Change Password</h3>
                <form method="POST" class="profile-form">
                    <input type="hidden" name="action" value="update_password">
                    
                    <div class="form-group">
                        <label for="old_password">Current Password</label>
                        <input type="password" 
                               id="old_password" 
                               name="old_password" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               required
                               minlength="6">
                        <small class="form-text">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               minlength="6">
                    </div>
                    
                    <button type="submit" class="btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '_footer.php';
?>

<!-- 加载 profile.js -->
<script src="../public/js/profile.js"></script>