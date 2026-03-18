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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- PAGE-SPECIFIC CSS -->
    <?php
    $cssMap = [
        'order'          => 'order.css',
        'order-history'  => 'order-history.css',
        'order-success'  => 'order-success.css',
        'update-order'   => 'order.css',
        'login'          => 'login-and-register.css',
        'register'       => 'login-and-register.css',
        'forgot-password' => 'login-and-register.css',
        'contact'        => 'contact.css',
        'invoice'        =>  'invoice.css',
        'change-password' => 'change-password.css',

    ];

    if (isset($cssMap[$page])) {
        echo '<link rel="stylesheet" href="assets/css/' . $cssMap[$page] . '">';
    }
    ?>
</head>

<body>