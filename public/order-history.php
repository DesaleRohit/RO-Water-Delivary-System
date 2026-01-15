<?php
session_start();
require_once __DIR__ . "/../app/config/database.php";

if (isset($_POST['mobile'])) {
    $_SESSION['track_mobile'] = trim($_POST['mobile']);
}

$mobile = $_SESSION['track_mobile'] ?? '';

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
    "SELECT id, quantity, delivery_date, status, order_date
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
                <th>Delivery Date</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>

            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?php echo $o['quantity']; ?></td>

                    <td><?php echo date("d M Y", strtotime($o['delivery_date'])); ?></td>

                    <td><?php echo ucfirst($o['status']); ?></td>

                    <td><?php echo date("d M Y, h:i A", strtotime($o['order_date'])); ?></td>

                    <td>
                        <?php if ($o['status'] === 'pending'): ?>
                            <a href="cancel-order.php?order_id=<?php echo $o['id']; ?>"
                                onclick="return confirm('Are you sure you want to cancel this order?');">
                                Cancel Order
                            </a>
                        <?php else: ?>
                            <?php echo ucfirst($o['status']); ?>
                        <?php endif; ?>
                    </td>

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