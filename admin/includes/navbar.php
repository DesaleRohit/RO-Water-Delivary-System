<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="admin-nav">
    <div class="nav-container">
        <div class="nav-left">
            <span class="brand">🚰 RO Admin</span>
        </div>

        <div class="nav-right">
            <a href="dashboard.php" class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="orders.php" class="nav-link <?= $current_page == 'orders.php' ? 'active' : '' ?>">Orders</a>
            <div class="user-menu">
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
    </div>
</nav>