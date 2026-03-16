<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = $_GET['page'] ?? 'home';
?>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php?page=home" class="logo">
            <i class="fas fa-droplet"></i> RO Service
        </a>

        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span>
        </label>

        <div class="nav-menu">
            <ul class="nav-links">
                <li><a href="index.php?page=home" class="<?= $current_page === 'home' ? 'active' : '' ?>">Home</a></li>

                <?php if (!isset($_SESSION['customer_id'])): ?>
                    <li><a href="index.php?page=login" class="<?= $current_page === 'login' ? 'active' : '' ?>">Login</a></li>
                    <li><a href="index.php?page=register" class="<?= $current_page === 'register' ? 'active' : '' ?>">Register</a></li>
                <?php else: ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            <i class="fas fa-user-circle"></i>
                            <span class="user-name"><?= htmlspecialchars($_SESSION['customer_name'] ?? 'Account') ?></span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?page=order" class="<?= $current_page === 'order' ? 'active' : '' ?>"><i class="fas fa-tint"></i> Order Water</a></li>
                            <li><a href="index.php?page=order-history" class="<?= $current_page === 'order-history' ? 'active' : '' ?>"><i class="fas fa-history"></i> Order History</a></li>
                            <li><a href="index.php?page=change-password" class="<?= $current_page === 'change-password' ? 'active' : '' ?>"><i class="fas fa-key"></i> Change Password</a></li>
                            <li><a href="index.php?page=contact" class="<?= $current_page === 'contact' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Contact Us</a></li>
                            <li><a href="index.php?page=logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>