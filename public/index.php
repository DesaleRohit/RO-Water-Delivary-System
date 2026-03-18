<?php
$page = $_GET['page'] ?? 'home';

$allowed_pages = [
    'home',
    'login',
    'register',
    'logout',
    'order',
    'order-success',
    'order-history',
    'update-order',
    'cancel-order',
    'contact',
    'invoice',
    'change-password',
    'forgot-password'
];
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