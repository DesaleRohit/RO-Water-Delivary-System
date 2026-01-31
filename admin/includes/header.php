<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel | RO Water Delivery</title>

    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="../public/assets/css/dashboard.css">
    <link rel="stylesheet" href="../public/assets/css/orders.css">

</head>

<body>

    <h1 style="margin:10px 0;">RO Water Delivery | Admin Panel</h1>
    <hr>