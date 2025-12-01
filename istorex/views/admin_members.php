<?php
include '_header.php';
?>

<link rel="stylesheet" href="../public/css/admin_members.css">

<div class="container admin-container">
    <h1>Member Management</h1>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="members-layout">
        <!-- 左侧：搜索 + 列表 -->
        <div class="members-list">
            <div class="members-header">
                <form method="GET" class="search-form">
                    <input type="text"
                           name="q"
                           value="<?php echo htmlspecialchars($search ?? ''); ?>"
                           placeholder="Search by name, username or email">
                    <button type="submit">Search</button>
                </form>
            </div>

            <?php if (empty($members)): ?>
                <div class="empty-state">
                    <p>No members found.</p>
                </div>
            <?php else: ?>
                <div class="members-table-wrapper">
                    <table class="members-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td>#<?php echo str_pad($member['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($member['full_name'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($member['username']); ?></td>
                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                    <td><?php echo htmlspecialchars($member['phone'] ?? '-'); ?></td>
                                    <td>
                                        <span class="role-badge role-<?php echo htmlspecialchars($member['role']); ?>">
                                            <?php echo ucfirst($member['role']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($member['created_at'])); ?></td>
                                    <td>
                                        <a href="admin_members.php?id=<?php echo $member['id']; ?>"
                                           class="btn-small">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- 右侧：详情 + 新会员注册 -->
        <div class="member-details-panel">
            <?php if ($selectedMember): ?>
                <div class="details-section">
                    <div class="details-header">
                        <h2>Member Detail</h2>
                        <a href="admin_members.php" class="close-btn">&times;</a>
                    </div>

                    <div class="member-profile">
                        <div class="avatar-wrapper">
                            <img src="../public/images/avatars/<?php echo htmlspecialchars($selectedMember['profile_image'] ?? ''); ?>"
                                 alt="Profile Image">
                        </div>
                        <div class="info">
                            <h3><?php echo htmlspecialchars($selectedMember['full_name'] ?? $selectedMember['username']); ?></h3>
                            <p>@<?php echo htmlspecialchars($selectedMember['username']); ?></p>
                            <p><?php echo htmlspecialchars($selectedMember['email']); ?></p>
                            <p><?php echo htmlspecialchars($selectedMember['phone'] ?? ''); ?></p>
                            <p>Role: <strong><?php echo ucfirst($selectedMember['role']); ?></strong></p>
                            <p>Joined: <?php echo date('M d, Y', strtotime($selectedMember['created_at'])); ?></p>
                        </div>
                    </div>

                    <div class="details-subsection">
                        <h3>Upload Profile Photo</h3>
                        <form method="POST" enctype="multipart/form-data" class="avatar-form">
                            <input type="hidden" name="action" value="upload_avatar_admin">
                            <input type="hidden" name="user_id" value="<?php echo $selectedMember['id']; ?>">
                            <input type="file" name="profile_image" accept="image/*" required>
                            <button type="submit" class="btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="details-section create-section">
                <h2>Register New Member</h2>
                <form method="POST" class="create-form">
                    <input type="hidden" name="action" value="create_member">

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group two-col">
                        <div>
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required minlength="6">
                        </div>
                        <div>
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Create Member</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '_footer.php';
?>


