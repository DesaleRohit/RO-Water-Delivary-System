<?php
require_once __DIR__ . "/../../app/config/database.php";

/* Validate order id */
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    echo "<div class='invoice-container'>
            <h2>Error</h2>
            <p>No order ID provided.</p>
            <a href='index.php?page=home'>Back Home</a>
          </div>";
    return;
}

/* Fetch order */
$stmt = $conn->prepare("
SELECT 
    o.id AS order_id,
    o.quantity,
    o.delivery_date,
    o.order_date,
    o.address AS delivery_address,
    c.name AS customer_name,
    c.mobile AS customer_mobile
FROM orders o
JOIN customers c ON o.customer_id = c.id
WHERE o.id = :id
");

$stmt->execute([':id' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<div class='invoice-container'>
            <h2>Error</h2>
            <p>Order not found.</p>
            <a href='index.php?page=home'>Back Home</a>
          </div>";
    return;
}

/* Price calculation */
$pricePerCan = 20;
$subtotal = $order['quantity'] * $pricePerCan;
$total = $subtotal;

/* Company info */
$companyName = "RO Water Delivery";
$companyAddress = "Maharashtra, India";
$companyPhone = "+91 98765 43210";
$companyEmail = "support@rowater.com";
?>

<div class="invoice-container">

    <div class="invoice-header">

        <div class="company-details">
            <h1><?= $companyName ?></h1>
            <p><?= $companyAddress ?></p>
            <p><?= $companyPhone ?> | <?= $companyEmail ?></p>
        </div>

        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> INV-<?= str_pad($orderId, 6, '0', STR_PAD_LEFT) ?></p>
            <p><strong>Order Date:</strong> <?= date("d M Y", strtotime($order['order_date'])) ?></p>
        </div>

    </div>

    <div class="customer-details">

        <h3>Bill To</h3>

        <p><strong><?= htmlspecialchars($order['customer_name']) ?></strong></p>

        <p><?= htmlspecialchars($order['delivery_address']) ?></p>

        <p>Mobile: <?= htmlspecialchars($order['customer_mobile']) ?></p>

    </div>

    <table class="invoice-items">

        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>

        <tbody>

            <tr>

                <td>
                    RO Water Can (20L) <br>
                    Delivery: <?= date("d M Y", strtotime($order['delivery_date'])) ?>
                </td>

                <td><?= $order['quantity'] ?></td>

                <td>₹<?= number_format($pricePerCan, 2) ?></td>

                <td>₹<?= number_format($subtotal, 2) ?></td>

            </tr>

        </tbody>

    </table>

    <div class="invoice-summary">

        <p>Subtotal: ₹<?= number_format($subtotal, 2) ?></p>

        <h3>Total: ₹<?= number_format($total, 2) ?></h3>

    </div>

    <div class="invoice-footer">
        <p>Thank you for your order.</p>
        <p>Payment will be collected upon delivery.</p>
    </div>

    <div class="invoice-actions">

        <button class="btn-print" onclick="window.print()">Print Invoice</button>

        <a href="index.php?page=order-history" class="btn-back" >Back to Orders</a>

    </div>

</div>