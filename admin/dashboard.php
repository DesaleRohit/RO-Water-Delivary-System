<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../app/config/database.php";


// Total orders
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Pending orders
$pendingOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending' ")->fetchColumn();

// Delivered orders
$deliveredOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'delivered' ")->fetchColumn();

// Total customers
$totalCustomers = $conn->query("SELECT COUNT(*) FROM customers")->fetchColumn();

//for Recent Orders 
$stmt = $conn->prepare("
    SELECT 
        orders.id,
        customers.name,
        customers.mobile,
        orders.quantity,
        orders.delivery_date,
        orders.status,
        orders.order_date
    FROM orders
    JOIN customers ON orders.customer_id = customers.id
    ORDER BY orders.order_date DESC
    LIMIT 5
");

$stmt->execute();

$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | RO Water Delivery</title>
</head>
<body>

<h1>Admin Dashboard</h1>
<p>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></p>

<hr>


<h3>Summary</h3>
<ul>
    <li>Total Orders: <strong><?php echo $totalOrders; ?></strong></li>
    <li>Pending Orders: <strong><?php echo $pendingOrders; ?></strong></li>
    <li>Delivered Orders: <strong><?php echo $deliveredOrders; ?></strong></li>
    <li>Total Customers: <strong><?php echo $totalCustomers; ?></strong></li>
</ul>

<hr>

<h3>Quick Navigation</h3>
<ul>
    <li><a href="orders.php">Manage Orders</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

<hr>

<!-- ===== Recent Orders ===== -->
<h3>Recent Orders</h3>

<?php if (count($recentOrders) > 0): ?>
<table border="1" cellpadding="8">
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Mobile</th>
        <th>Qty</th>
        <th>Delivery Date</th>
        <th>Status</th>
        <th>Booking Date</th>
    </tr>

    <?php foreach ($recentOrders as $order): ?>
    <tr>
        <td><?php echo $order['id']; ?></td>
        <td><?php echo htmlspecialchars($order['name']); ?></td>
        <td><?php echo htmlspecialchars($order['mobile']); ?></td>
        <td><?php echo $order['quantity']; ?></td>
         <td><?php echo $order['delivery_date']; ?></td>
        <td><?php echo ucfirst($order['status']); ?></td>
        <td><?php echo $order['order_date']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No recent orders.</p>
<?php endif; ?>

</body>
</html>
