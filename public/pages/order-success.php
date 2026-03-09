<?php
require_once __DIR__ . "/../../app/config/database.php";

// Check if order_id is provided
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    header("Location: index.php?page=home");
    exit;
}

$orderId = $_GET['order_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT quantity, delivery_date FROM orders WHERE id = :id");
$stmt->execute([':id' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// If order not found, redirect
if (!$order) {
    header("Location: index.php?page=home");
    exit;
}

$pricePerCan = 20;
$totalPrice = $order['quantity'] * $pricePerCan;
?>

<section class="success-section">
    <div class="success-header">
        <span class="success-icon">✓</span>
        <h2>Order Placed Successfully!</h2>
    </div>

    <p class="success-message">Your water can order has been received. Our team will deliver it on the selected date.</p>

    <div class="bill-box">
        <h3>Order Summary</h3>
        <div class="bill-row">
            <span class="bill-label">Order ID:</span>
            <span class="bill-value">#<?php echo htmlspecialchars($orderId); ?></span>
        </div>
        <div class="bill-row">
            <span class="bill-label">Quantity:</span>
            <span class="bill-value"><?php echo $order['quantity']; ?> can(s)</span>
        </div>
        <div class="bill-row">
            <span class="bill-label">Price per Can:</span>
            <span class="bill-value">₹<?php echo $pricePerCan; ?></span>
        </div>
        <div class="bill-row total">
            <span class="bill-label">Total Amount:</span>
            <span class="bill-value">₹<?php echo $totalPrice; ?></span>
        </div>
        <div class="bill-row">
            <span class="bill-label">Delivery Date:</span>
            <span class="bill-value"><?php echo date("d M Y", strtotime($order['delivery_date'])); ?></span>
        </div>
    </div>

    <div class="success-actions">
        <a href="index.php?page=home" class="btn-primary">Back to Home</a>
        <a href="index.php?page=order-history" class="btn-secondary">View Order History</a>
        
        <a href="index.php?page=invoice&order_id=<?php echo $orderId; ?>" class="btn-secondary">📄 View Invoice</a>
    </div>
</section>