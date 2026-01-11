<?php
require_once __DIR__ . "/../app/config/database.php";

$mobile = trim($_POST['mobile'] ?? '');

if ($mobile === '') {
    die("Mobile number is required.");
}

// Find customer by mobile
$stmt = $conn->prepare("SELECT id, name FROM customers WHERE mobile = :mobile");
$stmt->execute([':mobile' => $mobile]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    die("No customer found with this mobile number.");
}

// Fetch orders for this customer
$stmt = $conn->prepare(
    "SELECT quantity, order_type, status, order_date
     FROM orders
     WHERE customer_id = :cid
     ORDER BY order_date DESC"
);
$stmt->execute([':cid' => $customer['id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>My Orders | RO Water Delivery</title>
</head>

<body>

    <h2>Order History</h2>
    <p>Customer: <strong><?php echo htmlspecialchars($customer['name']); ?></strong></p>
    <p>Mobile: <strong><?php echo htmlspecialchars($mobile); ?></strong></p>

    <?php if (count($orders) > 0): ?>

        <table border="1" cellpadding="10">
            <tr>
                <th>Quantity</th>
                <th>Order Type</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>

            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?php echo $o['quantity']; ?></td>
                    <td><?php echo ucfirst($o['order_type']); ?></td>
                    <td><?php echo ucfirst($o['status']); ?></td>
                    <td><?php echo $o['order_date']; ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <br>
    <a href="track-order.php"> Track another number</a> |
    <a href="index.php">Home</a>

</body>

</html>