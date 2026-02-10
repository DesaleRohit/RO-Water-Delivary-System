<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../app/config/database.php';

$customerId = $_SESSION['customer_id'];

$stmt = $conn->prepare(
    "SELECT id, quantity, delivery_date, status, order_date
     FROM orders
     WHERE customer_id = :cid
     ORDER BY order_date DESC"
);
$stmt->execute([':cid' => $customerId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="order-history-section">

    <h2>My Orders</h2>

    <?php if (!empty($_SESSION['order_update_success'])): ?>
        <div class="success-message">
            Order updated successfully.
        </div>
        <?php unset($_SESSION['order_update_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['order_cancel_success'])): ?>
        <div class="success-message cancel">
            Order cancelled successfully.
        </div>
        <?php unset($_SESSION['order_cancel_success']); ?>
    <?php endif; ?>

    <?php if ($orders): ?>

        <table class="order-table">
            <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>

                        <td data-label="Quantity">
                            <?php echo $o['quantity']; ?>
                        </td>

                        <td data-label="Delivery Date">
                            <?php echo date("d M Y", strtotime($o['delivery_date'])); ?>
                        </td>

                        <td data-label="Status">
                            <span class="status <?php echo htmlspecialchars($o['status']); ?>">
                                <?php echo ucfirst($o['status']); ?>
                            </span>
                        </td>

                        <td data-label="Order Date">
                            <?php echo date("d M Y", strtotime($o['order_date'])); ?>
                        </td>

                        <td data-label="Action">
                            <?php if ($o['status'] === 'pending'): ?>
                                <a href="index.php?page=update-order&order_id=<?php echo $o['id']; ?>">
                                    Update
                                </a>
                                &nbsp;|&nbsp;
                                <a href="index.php?page=cancel-order&order_id=<?php echo $o['id']; ?>"
                                    onclick="return confirm('Are you sure you want to cancel this order?');">
                                    Cancel
                                </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p class="error-message">No orders found.</p>
    <?php endif; ?>

    <div class="history-actions">
        <a href="index.php?page=order" class="back-link">Place New Order</a>
        <a href="index.php?page=home" class="back-link">Back to Home</a>
    </div>

</section>