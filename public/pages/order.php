<?php
require_once __DIR__ . '/../../app/config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name          = trim($_POST['name']);
    $mobile        = trim($_POST['mobile']);
    $address       = trim($_POST['address']);
    $quantity      = (int) $_POST['quantity'];
    $delivery_date = $_POST['delivery_date'];

    /* Validation */
    if ($delivery_date < date('Y-m-d')) {
        $message = "Invalid delivery date selected.";
    } elseif ($name && $mobile && $address && $quantity > 0 && $delivery_date) {

        /* 1. Check customer */
        $stmt = $conn->prepare(
            "SELECT id FROM customers WHERE mobile = :mobile"
        );
        $stmt->execute([':mobile' => $mobile]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            $customer_id = $customer['id'];
        } else {
            /* 2. Insert new customer */
            $stmt = $conn->prepare(
                "INSERT INTO customers (name, mobile, address)
                 VALUES (:name, :mobile, :address)"
            );
            $stmt->execute([
                ':name'    => $name,
                ':mobile'  => $mobile,
                ':address' => $address
            ]);

            $customer_id = $conn->lastInsertId();
        }

        /* 3. Insert order WITH address */
        $stmt = $conn->prepare(
            "INSERT INTO orders (customer_id, quantity, delivery_date, address, status)
             VALUES (:customer_id, :quantity, :delivery_date, :address, 'pending')"
        );

        $stmt->execute([
            ':customer_id'  => $customer_id,
            ':quantity'     => $quantity,
            ':delivery_date'=> $delivery_date,
            ':address'      => $address
        ]);

        /* 4. Redirect */
        header("Location: index.php?page=order-success");
        exit;
    } else {
        $message = "All fields are required.";
    }
}
?>

<!-- ================= ORDER FORM ================= -->

<section class="order-section">
    <h2>Order RO Water Can</h2>

    <?php if ($message): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" class="order-form">

        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Mobile Number</label>
        <input type="text" name="mobile" required>

        <label>Delivery Address</label>
        <textarea name="address" required></textarea>

        <label>Number of Water Cans (20L)</label>
        <input type="number" name="quantity" min="1" required>

        <label>Delivery Date</label>
        <input type="date" name="delivery_date" required>

        <button type="submit">Place Order</button>
    </form>

    <a href="index.php?page=home" class="back-link">← Back to Home</a>
</section>
