<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="nav-container">
        <div class="logo">RO Service</div>

        <ul class="nav-links">

            <li><a href="index.php?page=home">Home</a></li>

            <?php if (!isset($_SESSION['customer_id'])): ?>

                <li><a href="index.php?page=login">Login</a></li>
                <li><a href="index.php?page=register">Register</a></li>

            <?php else: ?>

                <li><a href="index.php?page=order">Order</a></li>
                <li><a href="index.php?page=order-history">History</a></li>
                <li><a href="index.php?page=logout">Logout</a></li>
                
            <?php endif; ?>
        </ul>
    </div>
</nav>