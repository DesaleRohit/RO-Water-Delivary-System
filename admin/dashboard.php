<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';

require_once __DIR__ . "/../app/config/database.php";

// Initialize variables with default values
$totalOrders = 0;
$pendingOrders = 0;
$deliveredOrders = 0;
$totalCustomers = 0;

$todayScheduled = ['orders' => 0, 'cans' => 0];
$todayDelivered = ['orders' => 0, 'cans' => 0, 'revenue' => 0];
$totalRevenue = 0;

$todaysOrders = [];

// Check if database connection exists
if (isset($conn) && $conn) {
    try {
        // Total orders
        $result = $conn->query("SELECT COUNT(*) FROM orders");
        if ($result) $totalOrders = $result->fetchColumn();

        // Pending orders
        $result = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
        if ($result) $pendingOrders = $result->fetchColumn();

        // Delivered orders
        $result = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'delivered'");
        if ($result) $deliveredOrders = $result->fetchColumn();

        // Total customers
        $result = $conn->query("SELECT COUNT(*) FROM customers");
        if ($result) $totalCustomers = $result->fetchColumn();

        // Today's scheduled deliveries
        $result = $conn->query("
            SELECT 
                COUNT(*) AS orders,
                COALESCE(SUM(quantity), 0) AS cans
            FROM orders
            WHERE DATE(delivery_date) = CURDATE()
        ");
        if ($result) $todayScheduled = $result->fetch(PDO::FETCH_ASSOC);

        // Today's delivered analytics
        $result = $conn->query("
            SELECT 
                COUNT(*) AS orders,
                COALESCE(SUM(quantity), 0) AS cans,
                COALESCE(SUM(quantity * 20), 0) AS revenue
            FROM orders
            WHERE DATE(delivery_date) = CURDATE()
              AND status = 'delivered'
        ");
        if ($result) $todayDelivered = $result->fetch(PDO::FETCH_ASSOC);

        // Total revenue (all delivered orders)
        $result = $conn->query("
            SELECT COALESCE(SUM(quantity * 20), 0)
            FROM orders
            WHERE status = 'delivered'
        ");
        if ($result) $totalRevenue = $result->fetchColumn();

        // Today's orders (delivery date)
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
    } catch (PDOException $e) {
        // Log error (optional) but keep variables at default values
        error_log("Dashboard DB error: " . $e->getMessage());
    }
} else {
    error_log("Dashboard: Database connection not available.");
}
?>

<main class="admin-main">
    <h2>Dashboard</h2>
    <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong></p>

    <!-- Summary Cards (no icons) -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-content">
                <h4>Total Orders</h4>
                <span class="value"><?php echo $totalOrders; ?></span>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-content">
                <h4>Pending</h4>
                <span class="value"><?php echo $pendingOrders; ?></span>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-content">
                <h4>Delivered</h4>
                <span class="value"><?php echo $deliveredOrders; ?></span>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-content">
                <h4>Customers</h4>
                <span class="value"><?php echo $totalCustomers; ?></span>
            </div>
        </div>
    </div>

    <h3>Today's Overview</h3>
    <div class="analytics-grid">
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

    <h3>Today's Deliveries</h3>
    <?php if (!empty($todaysOrders)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Qty</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Booked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todaysOrders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['name']); ?></td>
                            <td><?php echo htmlspecialchars($order['mobile']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td><?php echo date("d M Y", strtotime($order['delivery_date'])); ?></td>
                            <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                            <td><?php echo date("d M Y", strtotime($order['order_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-data">No deliveries scheduled for today.</p>
    <?php endif; ?>
</main>