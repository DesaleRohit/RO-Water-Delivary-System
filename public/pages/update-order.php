<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../app/config/database.php';


//    VALIDATE ORDER ID


if (!isset($_GET['order_id'])) {
    echo "<p class='error-message'>Invalid request.</p>";
    return;
}

$orderId = (int) $_GET['order_id'];
$customerId = $_SESSION['customer_id'];


//    FETCH ORDER (OWNERSHIP + STATUS CHECK)


$stmt = $conn->prepare(
    "SELECT id, quantity, delivery_date, address, status
     FROM orders
     WHERE id = :oid
       AND customer_id = :cid"
);

$stmt->execute([
    ':oid' => $orderId,
    ':cid' => $customerId
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

//    VALIDATIONS


if (!$order) {
    echo "<p class='error-message'>Order not found.</p>";
    return;
}

if ($order['status'] !== 'pending') {
    echo "<p class='error-message'>Only pending orders can be updated.</p>";
    return;
}


//    HANDLE UPDATE

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

        $stmt = $conn->prepare(
            "UPDATE orders
             SET quantity = :qty,
                 delivery_date = :dd,
                 address = :addr
             WHERE id = :oid
               AND customer_id = :cid
               AND status = 'pending'"
        );

        $stmt->execute([
            ':qty' => $quantity,
            ':dd'  => $delivery_date,
            ':addr' => $address,
            ':oid' => $orderId,
            ':cid' => $customerId
        ]);

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
        Back to Order History
    </a>

</section>