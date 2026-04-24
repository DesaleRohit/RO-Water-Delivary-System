<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$page = $_GET['page'] ?? 'dashboard';

$allowed_pages = [
    'dashboard',
    'orders',
    'customers',
    'messages',
    'change-password'
];

// Handle logout via a special page (optional)
if ($page === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="admin-main">
    <?php
    if (in_array($page, $allowed_pages)) {
        $page_file = "pages/$page.php";
        if (file_exists($page_file)) {
            include $page_file;
        } else {
            echo "<h2 style='text-align:center;'>Page file not found</h2>";
        }
    } else {
        echo "<h2 style='text-align:center;'>Page Not Found</h2>";
    }
    ?>
</main>
<!-- 
<?php include 'includes/footer.php'; ?> -->