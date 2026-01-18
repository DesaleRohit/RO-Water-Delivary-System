<?php
$page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RO Water Delivery System</title>

    <!-- GLOBAL CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- PAGE-SPECIFIC CSS -->
    <?php
    $cssMap = [
        'order'          => 'order.css',
        'track-order'    => 'track-order.css',
        'order-history'  => 'order-history.css',
        'order-success'  => 'order-success.css',
        'update-order'   => 'order.css',
    ];

    if (isset($cssMap[$page])) {
        echo '<link rel="stylesheet" href="assets/css/' . $cssMap[$page] . '">';
    }
    ?>
</head>

<body>