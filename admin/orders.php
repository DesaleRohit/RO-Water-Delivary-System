<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$pricePerCan = 20;
require_once __DIR__ . "/../app/config/database.php";

// Handle status update (same logic)
if (isset($_GET['deliver_id'])) {
    $orderId = (int) $_GET['deliver_id'];
    $stmt = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE id = :id AND status = 'pending'");
    $stmt->execute([':id' => $orderId]);
    header("Location: orders.php");
    exit;
}

// Fetch all orders (same query)
$sql = "SELECT orders.id AS order_id, customers.name, customers.mobile, orders.address, orders.quantity, orders.delivery_date, orders.status, orders.order_date FROM orders JOIN customers ON orders.customer_id = customers.id ORDER BY orders.order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="admin-main">
    <h2>All Orders</h2>

    <?php if (!empty($orders)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Qty</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Booked</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['order_id'] ?></td>
                            <td><?= htmlspecialchars($order['name']) ?></td>
                            <td><?= htmlspecialchars($order['mobile']) ?></td>
                            <td><?= htmlspecialchars($order['address']) ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td><?= date("d M Y", strtotime($order['delivery_date'])) ?></td>
                            <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                            <td>₹<?= ((int)$order['quantity']) * $pricePerCan ?></td>
                            <td><?= date("d M Y", strtotime($order['order_date'])) ?></td>
                            <td>
                                <?php if ($order['status'] === 'pending'): ?>
                                    <a class="action-link" href="orders.php?deliver_id=<?= $order['order_id'] ?>">Mark Delivered</a>
                                <?php else: ?>
                                    <span class="status-text"><?= ucfirst($order['status']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-data">No orders found.</p>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>