<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine current page for active state
$current_page = $_GET['page'] ?? 'home';
?>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php?page=home" class="logo">RO Service</a>

        <!-- Hamburger menu (visible on mobile) -->
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
                    <!-- User dropdown -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            <span class="user-name"><?= htmlspecialchars($_SESSION['customer_name'] ?? 'Account') ?></span>
                            <span class="dropdown-arrow">▼</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?page=order" class="<?= $current_page === 'order' ? 'active' : '' ?>">Order Water</a></li>
                            <li><a href="index.php?page=order-history" class="<?= $current_page === 'order-history' ? 'active' : '' ?>">Order History</a></li>
                            <li><a href="index.php?page=contact" class="<?= $current_page === 'contact' ? 'active' : '' ?>">Contact Us</a></li>
                            <li><a href="index.php?page=logout" class="logout-link">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>