<?php
session_start();
require_once __DIR__ . '/../../app/config/database.php';

/* Check order id */
if (!isset($_GET['order_id'])) {
    header("Location: index.php?page=order-history");
    exit;
}

$orderId = (int) $_GET['order_id'];

/* Ensure mobile session exists */
if (!isset($_SESSION['track_mobile'])) {
    header("Location: index.php?page=track");
    exit;
}

$mobile = $_SESSION['track_mobile'];

/* Verify order belongs to this customer */
$stmt = $conn->prepare("
   SELECT orders.id
    FROM orders
    JOIN customers ON orders.customer_id = customers.id
    WHERE orders.id = :oid
    AND customers.mobile = :mobile
    AND orders.status = 'pending'
");
$stmt->execute([
    ':oid' => $orderId,
    ':mobile' => $mobile
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: index.php?page=order-history");
    exit;
}

/* Cancel order */
$stmt = $conn->prepare("
    UPDATE orders
    SET status = 'cancelled'
    WHERE id = :id
");
$stmt->execute([':id' => $orderId]);

/* Redirect back */
$_SESSION['order_cancel_success'] = true;
header("Location: index.php?page=order-history");
exit;
