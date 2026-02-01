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


//    TODAY'S ORDERS (DELIVERY DATE)

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
    WHERE DATE(orders.delivery_date) = CURDATE()
    ORDER BY orders.delivery_date DESC
");
$stmt->execute();
$todaysOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

//    DASHBOARD ANALYTICS

// A) Today's Scheduled Deliveries (Workload)
$todayScheduled = $conn->query("
    SELECT 
        COUNT(*) AS orders,
        COALESCE(SUM(quantity), 0) AS cans
    FROM orders
    WHERE DATE(delivery_date) = CURDATE()
")->fetch(PDO::FETCH_ASSOC);

// B) Today's Delivered Analytics (Revenue)
$todayDelivered = $conn->query("
    SELECT 
        COUNT(*) AS orders,
        COALESCE(SUM(quantity), 0) AS cans,
        COALESCE(SUM(quantity * 20), 0) AS revenue
    FROM orders
    WHERE DATE(delivery_date) = CURDATE()
      AND status = 'delivered'
")->fetch(PDO::FETCH_ASSOC);

// Overall revenue (Delivered only)
$totalRevenue = $conn->query("
    SELECT COALESCE(SUM(quantity * 20), 0)
    FROM orders
    WHERE status = 'delivered'
")->fetchColumn();
?>

<h2>Dashboard</h2>
<p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></p>

<!-- SUMMARY -->

<h3>Summary</h3>
<ul>
    <li>Total Orders <strong><?php echo $totalOrders; ?></strong></li>
    <li>Pending Orders <strong><?php echo $pendingOrders; ?></strong></li>
    <li>Delivered Orders <strong><?php echo $deliveredOrders; ?></strong></li>
    <li>Total Customers <strong><?php echo $totalCustomers; ?></strong></li>
</ul>


<!-- TODAY'S ANALYTICS -->

<h3>Today's Overview</h3>

<div class="analytics-cards">
    <div class="analytics-card">
        <span class="label">Scheduled Orders</span>
        <span class="value"><?php echo $todayScheduled['orders']; ?></span>
    </div>

    <div class="analytics-card">
        <span class="label">Scheduled Cans</span>
        <span class="value"><?php echo $todayScheduled['cans']; ?></span>
    </div>

    <div class="analytics-card">
        <span class="label">Delivered Orders</span>
        <span class="value"><?php echo $todayDelivered['orders']; ?></span>
    </div>

    <div class="analytics-card">
        <span class="label">Delivered Cans</span>
        <span class="value"><?php echo $todayDelivered['cans']; ?></span>
    </div>

    <div class="analytics-card">
        <span class="label">Today's Revenue</span>
        <span class="value">₹<?php echo $todayDelivered['revenue']; ?></span>
    </div>

    <div class="analytics-card highlight">
        <span class="label">Total Revenue</span>
        <span class="value">₹<?php echo $totalRevenue; ?></span>
    </div>
</div>

<hr>

<!-- TODAY'S ORDERS TABLE -->

<h3>Today's Deliveries</h3>

<?php if (!empty($todaysOrders)): ?>
    <table cellspacing="0" cellpadding="8">
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
    <p class="no-data">No deliveries scheduled for today.</p>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>
