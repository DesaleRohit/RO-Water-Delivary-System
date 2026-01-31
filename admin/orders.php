<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$pricePerCan = 20;

require_once __DIR__ . "/../app/config/database.php";

// Handle status update (Mark as Delivered)
if (isset($_GET['deliver_id'])) {
    $orderId = (int) $_GET['deliver_id'];

    $stmt = $conn->prepare(
        "UPDATE orders 
         SET status = 'delivered' 
         WHERE id = :id AND status = 'pending'"
    );
    $stmt->execute([':id' => $orderId]);

    header("Location: orders.php");
    exit;
}

// Fetch all orders
$sql = "
    SELECT 
        orders.id AS order_id,
        customers.name,
        customers.mobile,
        orders.address,
        orders.quantity,
        orders.delivery_date,
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

<h2>All Customer Orders</h2>

<?php if (!empty($orders)): ?>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Quantity</th>
            <th>Delivery Date</th>
            <th>Status</th>
            <th>Total Amount (₹)</th>
            <th>Booking Date</th>
            <th>Action</th>
        </tr>

        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>

                <td><?php echo htmlspecialchars($order['name']); ?></td>

                <td><?php echo htmlspecialchars($order['mobile']); ?></td>

                <td><?php echo htmlspecialchars($order['address']); ?></td>

                <td><?php echo $order['quantity']; ?></td>

                <td><?php echo date("d M Y", strtotime($order['delivery_date'])); ?></td>

                <td>
                    <span class="status-<?php echo $order['status']; ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </td>

                <td>
                    ₹<?php echo ((int)$order['quantity']) * $pricePerCan; ?>
                </td>

                <td><?php echo date("d M Y", strtotime($order['order_date'])); ?></td>

                <td>
                    <?php if ($order['status'] === 'pending'): ?>

                      <a class="action-link" href="orders.php?deliver_id=<?php echo $order['order_id']; ?>">

                          <span class="status-text">Delivered</span>

                        </a>

                    <?php elseif ($order['status'] === 'delivered'): ?>

                        Delivered

                    <?php elseif ($order['status'] === 'cancelled'): ?>

                        Cancelled

                    <?php else: ?>

                        —

                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

<?php else: ?>
    <p class="no-data">No orders found.</p>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>