<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <span class="brand">🚰 RO Admin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>

        <a href="orders.php" class="nav-link <?= $current_page == 'orders.php' ? 'active' : '' ?>">Orders</a>

        <a href="customer-messages.php" class="nav-link <?= $current_page == 'customer-messages.php' ? 'active' : '' ?>">Messages</a>

        <a href="admin-change-password.php" class="nav-link <?= $current_page == 'admin-change-password.php' ? 'active' : '' ?>">Change Password</a>
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