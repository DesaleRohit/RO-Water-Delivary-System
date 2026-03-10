<?php
// No session check here – it's already done in index.php
$current_page = $_GET['page'] ?? 'dashboard'; // read from query string
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <span class="brand">🚰 RO Admin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php?page=dashboard" class="nav-link <?= $current_page == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="index.php?page=orders" class="nav-link <?= $current_page == 'orders' ? 'active' : '' ?>">Orders</a>
        <a href="index.php?page=messages" class="nav-link <?= $current_page == 'messages' ? 'active' : '' ?>">Messages</a>
        <a href="index.php?page=change-password" class="nav-link <?= $current_page == 'change-password' ? 'active' : '' ?>">Change Password</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <span class="user-name"><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            <a href="logout.php" class="logout-link" title="Logout">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </a>
        </div>
    </div>
</aside>