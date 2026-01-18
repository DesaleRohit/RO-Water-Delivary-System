<?php
session_start();
require_once __DIR__ . '/../../app/config/database.php';

if (isset($_POST['mobile'])) {
    $_SESSION['track_mobile'] = trim($_POST['mobile']);
}

$mobile = $_SESSION['track_mobile'] ?? '';

if ($mobile === '') {
    echo "<p class='error-message'>Mobile number is required.</p>";
    return;
}

/* Find customer */
$stmt = $conn->prepare(
    "SELECT id, name FROM customers WHERE mobile = :mobile"
);
$stmt->execute([':mobile' => $mobile]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "<p class='error-message'>No customer found with this mobile number.</p>";
    return;
}

/* Fetch orders */
$stmt = $conn->prepare(
    "SELECT id, quantity, delivery_date, status, order_date
     FROM orders
     WHERE customer_id = :cid
     ORDER BY order_date DESC"
);
$stmt->execute([':cid' => $customer['id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="order-history-section">

    <h2>Order History</h2>

    <p>
        Customer: <strong><?= htmlspecialchars($customer['name']) ?></strong><br>
        Mobile: <strong><?= htmlspecialchars($mobile) ?></strong>
    </p>

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
                        <td data-label="Quantity"><?= $o['quantity'] ?></td>

                        <td data-label="Delivery Date"><?= date("d M Y", strtotime($o['delivery_date'])) ?></td>

                        <td data-label="Status" class="status <?= htmlspecialchars($o['status']) ?>">
                            <?= ucfirst($o['status']) ?>
                        </td>

                        <td data-label="Order Date"><?= date("d M Y, h:i A", strtotime($o['order_date'])) ?></td>

                        <td data-label="Action">
                            <?php if ($o['status'] === 'pending'): ?>

                                <a href="index.php?page=update-order&order_id=<?= $o['id'] ?>">
                                    Update
                                </a>
                                &nbsp;|&nbsp;
                                <a href="index.php?page=cancel-order&order_id=<?= $o['id'] ?>"
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
        <p>No orders found.</p>
    <?php endif; ?>

    <div class="history-actions">
        <a href="index.php?page=track-order" class="back-link">Track Another Number</a>
        <a href="index.php?page=home" class="back-link">Back to Home</a>
    </div>

</section>