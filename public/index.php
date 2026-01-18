<?php
// Default page
$page = $_GET['page'] ?? 'home';

// Allowed pages (security)
$allowed_pages = ['home', 'order', 'track-order', 'order-success', 'order-history', 'cancel-order', 'update-order'];
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main class="main-container">
    <?php
    if (in_array($page, $allowed_pages)) {
        include "pages/$page.php";
    } else {
        echo "<h2 style='text-align:center;'>Page Not Found</h2>";
    }
    ?>
</main>

<?php include 'includes/footer.php'; ?>