<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../app/config/database.php';

//    VALIDATE ORDER ID


if (!isset($_GET['order_id'])) {
    header("Location: index.php?page=order-history");
    exit;
}

$orderId = (int) $_GET['order_id'];
$customerId = $_SESSION['customer_id'];


//    VERIFY ORDER OWNERSHIP
 

$stmt = $conn->prepare(
    "SELECT id
     FROM orders
     WHERE id = :oid
       AND customer_id = :cid
       AND status = 'pending'"
);

$stmt->execute([
    ':oid' => $orderId,
    ':cid' => $customerId
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: index.php?page=order-history");
    exit;
}

//    CANCEL ORDER

$stmt = $conn->prepare(
    "UPDATE orders
     SET status = 'cancelled'
     WHERE id = :oid
       AND customer_id = :cid"
);

$stmt->execute([
    ':oid' => $orderId,
    ':cid' => $customerId
]);

//    REDIRECT BACK

$_SESSION['order_cancel_success'] = true;
header("Location: index.php?page=order-history");
exit;
?>