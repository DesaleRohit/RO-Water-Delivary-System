<?php
require_once "../app/config/database.php";

if (!isset($_GET['order_id'])) {
    die("Invalid request");
}

$orderId = (int) $_GET['order_id'];

// Cancel only if order is pending
$stmt = $conn->prepare("
    UPDATE orders 
    SET status = 'cancelled'
    WHERE id = :id AND status = 'pending'
");
$stmt->execute([':id' => $orderId]);

// Redirect back to order history page
header("Location: order-history.php");
exit;
