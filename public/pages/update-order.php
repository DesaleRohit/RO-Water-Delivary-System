<?php
session_start();
require_once __DIR__ . '/../../app/config/database.php';

// Validate order_id

if (!isset($_GET['order_id'])) {
    echo "<p class='error-message'>Invalid request.</p>";
    return;
}

$orderId = (int) $_GET['order_id'];

// Validate customer session

$mobile = $_SESSION['track_mobile'] ?? '';
if ($mobile === '') {
    echo "<p class='error-message'>Session expired. Please track again.</p>";
    return;
}

// Fetch order + ownership check

$stmt = $conn->prepare(
    "SELECT 
        orders.id,
        orders.quantity,
        orders.delivery_date,
        orders.address,
        orders.status
     FROM orders
     JOIN customers ON orders.customer_id = customers.id
     WHERE orders.id = :oid
       AND customers.mobile = :mobile"
);

$stmt->execute([
    ':oid'    => $orderId,
    ':mobile' => $mobile
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Validate order

if (!$order) {
    echo "<p class='error-message'>Order not found.</p>";
    return;
}

if ($order['status'] !== 'pending') {
    echo "<p class='error-message'>Only pending orders can be updated.</p>";
    return;
}

// Handle update submission

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $quantity      = (int) $_POST['quantity'];
    $delivery_date = $_POST['delivery_date'];
    $address       = trim($_POST['address']);

    if ($quantity < 1) {
        $message = "Quantity must be at least 1.";
    } elseif ($delivery_date < date('Y-m-d')) {
        $message = "Invalid delivery date selected.";
    } elseif ($address === '') {
        $message = "Delivery address is required.";
    } else {


        // Update order (SAFE)

        $stmt = $conn->prepare(
            "UPDATE orders
             SET quantity = :qty,
                 delivery_date = :dd,
                 address = :addr
             WHERE id = :oid
               AND status = 'pending'"
        );

        $stmt->execute([
            ':qty'  => $quantity,
            ':dd'   => $delivery_date,
            ':addr' => $address,
            ':oid'  => $orderId
        ]);


        // Redirect back
        $_SESSION['order_update_success'] = true;
        header("Location: index.php?page=order-history");
        exit;
    }
}
?>

<!-- UPDATE ORDER FORM -->

<section class="order-section">

    <h2>Update Order</h2>

    <?php if ($message): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" class="order-form">

        <label>Number of Water Cans</label>
        <input type="number" name="quantity" min="1"
            value="<?= htmlspecialchars($order['quantity']) ?>" required>

        <label>Delivery Date</label>
        <input type="date" name="delivery_date"
            value="<?= htmlspecialchars($order['delivery_date']) ?>" required>

        <label>Delivery Address</label>
        <textarea name="address" required><?= htmlspecialchars($order['address']) ?></textarea>

        <button type="submit">Update Order</button>
    </form>

    <a href="index.php?page=order-history" class="back-link">
        ← Back to Order History
    </a>

</section>