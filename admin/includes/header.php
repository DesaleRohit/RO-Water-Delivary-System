<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $page is set in admin/index.php before including this header
$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | RO Water Delivery</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <!-- GLOBAL ADMIN CSS -->
    <link rel="stylesheet" href="../public/assets/css/dashboard.css">

    <!-- Font Awesome (optional, for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- PAGE-SPECIFIC CSS -->
    <?php
    $adminCssMap = [
        'orders'          => 'orders.css',
        'messages'        => 'messages.css',
        'change-password' => 'admin-change-pass.css'
        // Add more mappings as needed
    ];

    if (isset($adminCssMap[$page])) {
        echo '<link rel="stylesheet" href="../public/assets/css/' . $adminCssMap[$page] . '">';
    }
    ?>
</head>

<body>