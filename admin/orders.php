<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . "/../app/config/database.php";

$sql = "
    SELECT 
        orders.id AS order_id,
        customers.name,
        customers.mobile,
        customers.address,
        orders.quantity,
        orders.order_type,
        orders.status,
        orders.order_date
    FROM orders
    JOIN customers ON orders.customer_id = customers.id
    ORDER BY orders.order_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin | View Orders</title>
</head>

<body>

    <h2>All Customer Orders</h2>

    <a href="../index.php">Back to Website</a> <br><br>
    <a href="logout.php">Logout</a>

    <br><br>

    <?php if (count($orders) > 0): ?>

        <table border="1" cellpadding="10">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Quantity</th>
                <th>Order Type</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>

            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td><?php echo htmlspecialchars($order['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo ucfirst($order['order_type']); ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

</body>

</html>