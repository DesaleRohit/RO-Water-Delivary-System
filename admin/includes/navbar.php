<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<nav class="admin-nav">
    <div class="nav-left">
        <span class="brand">RO Admin</span>
    </div>

    <div class="nav-right">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="orders.php" class="nav-link">Orders</a>
        <a href="logout.php" class="nav-link logout">Logout</a>
    </div>
</nav>
