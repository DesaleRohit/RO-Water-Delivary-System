<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../app/config/database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_id  = $_SESSION['customer_id'];
    $address      = trim($_POST['address']);
    $quantity     = (int) $_POST['quantity'];
    $deliveryDate = $_POST['delivery_date'];

    if ($deliveryDate < date('Y-m-d')) {
        $message = "Invalid delivery date.";
    } elseif ($quantity < 1 || $address === '') {
        $message = "All fields required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO orders (customer_id, quantity, delivery_date, address, status)
             VALUES (:cid, :qty, :dd, :addr, 'pending')"
        );
        $stmt->execute([
            ':cid'  => $customer_id,
            ':qty'  => $quantity,
            ':dd'   => $deliveryDate,
            ':addr' => $address
        ]);

        $orderId = $conn->lastInsertId();

        header("Location: index.php?page=order-success&order_id=" . $orderId);
        exit;
    }
}
?>

<section class="order-section">

    <h2>Order Water Can</h2>

    <form method="post" class="order-form">

        <label>Delivery Address</label>
        <textarea name="address" required></textarea>

        <label>Number of Water Cans (20L)</label>
        <input type="number" name="quantity" min="1" required>

        <label>Delivery Date</label>
        <input type="date" name="delivery_date" required>

        <button type="submit">Place Order</button>
    </form>

    <a href="index.php?page=home" class="back-link">Back to Home</a>

</section>