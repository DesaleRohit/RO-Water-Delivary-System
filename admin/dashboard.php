<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';

require_once __DIR__ . "/../app/config/database.php";

// Total orders
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Pending orders
$pendingOrders = $conn->query(
    "SELECT COUNT(*) FROM orders WHERE status = 'pending'"
)->fetchColumn();

// Delivered orders
$deliveredOrders = $conn->query(
    "SELECT COUNT(*) FROM orders WHERE status = 'delivered'"
)->fetchColumn();

// Total customers
$totalCustomers = $conn->query(
    "SELECT COUNT(*) FROM customers"
)->fetchColumn();

// Today's orders
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
    WHERE DATE(orders.order_date) = CURDATE()
    ORDER BY orders.order_date DESC
");
$stmt->execute();
$todaysOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Dashboard</h2>
<p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></p>

<!-- ===== Summary Section ===== -->
<h3>Summary</h3>
<ul>
    <li>Total Orders: <strong><?php echo $totalOrders; ?></strong></li>
    <li>Pending Orders: <strong><?php echo $pendingOrders; ?></strong></li>
    <li>Delivered Orders: <strong><?php echo $deliveredOrders; ?></strong></li>
    <li>Total Customers: <strong><?php echo $totalCustomers; ?></strong></li>
</ul>

<hr>

<!-- ===== Today's Orders ===== -->
<h3>Today's Orders</h3>

<?php if (!empty($todaysOrders)): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Mobile</th>
            <th>Quantity</th>
            <th>Delivery Date</th>
            <th>Status</th>
            <th>Booking Date</th>
        </tr>

        <?php foreach ($todaysOrders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['name']); ?></td>
                <td><?php echo htmlspecialchars($order['mobile']); ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo date("d M Y", strtotime($order['delivery_date'])); ?></td>
                <td><?php echo ucfirst($order['status']); ?></td>
                <td><?php echo date("d M Y", strtotime($order['order_date'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No orders placed today.</p>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>
