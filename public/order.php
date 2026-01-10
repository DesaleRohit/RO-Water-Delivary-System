<?php
require_once "../app/config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $quantity = (int)$_POST['quantity'];
    $order_type = $_POST['order_type'];

    if ($name && $mobile && $address && $quantity > 0 && $order_type) {

        // Check if customer already exists (by mobile)
        $stmt = $conn->prepare("SELECT id FROM customers WHERE mobile = :mobile");
        $stmt->bindParam(":mobile", $mobile);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            // Existing customer
            $customer_id = $customer['id'];
        } else {
            // New customer
            $stmt = $conn->prepare(
                "INSERT INTO customers (name, mobile, address)
                 VALUES (:name, :mobile, :address)"
            );
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":mobile", $mobile);
            $stmt->bindParam(":address", $address);
            $stmt->execute();

            $customer_id = $conn->lastInsertId();
        }

        // Insert order
        $stmt = $conn->prepare(
            "INSERT INTO orders (customer_id, quantity, order_type)
             VALUES (:customer_id, :quantity, :order_type)"
        );
        $stmt->bindParam(":customer_id", $customer_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":order_type", $order_type);
        $stmt->execute();

        // Redirect to success page
        header("Location: order-success.php");
        exit;
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Water Can | RO Water Delivery</title>
</head>

<body>

    <h2>Order RO Water Can</h2>

    <?php if ($message): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">

        <label>Full Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Mobile Number:</label><br>
        <input type="text" name="mobile" required><br><br>

        <label>Delivery Address:</label><br>
        <textarea name="address" required></textarea><br><br>

        <label>Number of Water Cans (20L):</label><br>
        <input type="number" name="quantity" min="1" required><br><br>

        <label>Order Type:</label><br>
        <select name="order_type" required>
            <option value="">-- Select --</option>
            <option value="one_time">One Time</option>
            <option value="daily">Daily</option>
            <option value="monthly">Monthly</option>
        </select><br><br>

        <button type="submit">Place Order</button>

    </form>

    <br>
    <a href="index.php">Back to Home</a>

</body>

</html>