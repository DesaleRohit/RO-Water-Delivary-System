<?php
require_once "../app/config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $quantity = (int)$_POST['quantity'];
    $delivery_date = $_POST['delivery_date'];

    if ($delivery_date < date('Y-m-d')) {
        die("Invalid delivery date selected.");
    }
    if ($name && $mobile && $address && $quantity > 0 && $delivery_date) {

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
            "INSERT INTO orders (customer_id, quantity, delivery_date)
             VALUES (:customer_id, :quantity, :delivery_date)"
        );
        $stmt->bindParam(":customer_id", $customer_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":delivery_date", $delivery_date);
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

        <label>Delivery Date:</label><br>
        <input type="date" name="delivery_date" id="delivery_date" required><br><br>
        <br><br>

        <button type="submit">Place Order</button>

    </form>

    <br>
    <a href="index.php">Back to Home</a>
    <script src="assets/js/script.js"></script>
</body>

</html>