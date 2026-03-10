<?php
$pricePerCan = 20;
require_once __DIR__ . "/../../app/config/database.php";

// Get search/filter parameters
$searchMobile = $_GET['mobile'] ?? '';
$filterStatus = $_GET['status'] ?? '';

// Handle status update (mark as delivered)
if (isset($_GET['deliver_id'])) {
    $orderId = (int) $_GET['deliver_id'];
    $stmt = $conn->prepare("UPDATE orders SET status = 'delivered' WHERE id = :id AND status = 'pending'");
    $stmt->execute([':id' => $orderId]);

    // Redirect back to the same page with current filters preserved
    $queryParams = [];
    if (!empty($searchMobile)) $queryParams['mobile'] = $searchMobile;
    if (!empty($filterStatus)) $queryParams['status'] = $filterStatus;
    $redirectUrl = 'index.php?page=orders';
    if (!empty($queryParams)) {
        $redirectUrl .= '&' . http_build_query($queryParams);
    }
    header("Location: $redirectUrl");
    exit;
}

// Build the SQL query with filters
$sql = "SELECT orders.id AS order_id, 
               customers.name, 
               customers.mobile,
               orders.address, 
               orders.quantity, 
               orders.delivery_date, 
               orders.status, 
               orders.order_date 
        FROM orders 
        JOIN customers ON orders.customer_id = customers.id";

$conditions = [];
$params = [];

// Add mobile filter if provided
if (!empty($searchMobile)) {
    $conditions[] = "customers.mobile LIKE :mobile";
    $params[':mobile'] = "%$searchMobile%";
}

// Add status filter if provided and not 'all'
if (!empty($filterStatus) && $filterStatus !== 'all') {
    $conditions[] = "orders.status = :status";
    $params[':status'] = $filterStatus;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY orders.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>All Orders</h2>

<!-- Search/Filter Bar -->
<div class="search-bar">
    <form method="get" action="index.php">
        <input type="hidden" name="page" value="orders">

        <div class="search-group">
            <input type="text"
                name="mobile"
                placeholder="Search by mobile number..."
                value="<?php echo htmlspecialchars($searchMobile); ?>"
                class="search-input">

            <select name="status" class="status-filter">
                <option value="all" <?php echo $filterStatus === 'all' || $filterStatus === '' ? 'selected' : ''; ?>>All Status</option>
                <option value="pending" <?php echo $filterStatus === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="delivered" <?php echo $filterStatus === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                <option value="cancelled" <?php echo $filterStatus === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>

            <button type="submit" class="search-btn">Filter</button>

            <?php if (!empty($searchMobile) || !empty($filterStatus)): ?>
                <a href="index.php?page=orders" class="clear-btn">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if (!empty($orders)): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Qty</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Booked</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['order_id'] ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                        <td><?= htmlspecialchars($order['mobile']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= date("d M Y", strtotime($order['delivery_date'])) ?></td>
                        <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                        <td>₹<?= ((int)$order['quantity']) * $pricePerCan ?></td>
                        <td><?= date("d M Y", strtotime($order['order_date'])) ?></td>
                        <td>
                            <?php if ($order['status'] === 'pending'): ?>
                                <?php
                                // Preserve filters in the deliver link
                                $deliverParams = ['page' => 'orders', 'deliver_id' => $order['order_id']];
                                if (!empty($searchMobile)) $deliverParams['mobile'] = $searchMobile;
                                if (!empty($filterStatus)) $deliverParams['status'] = $filterStatus;
                                $deliverUrl = 'index.php?' . http_build_query($deliverParams);
                                ?>
                                <a class="action-link" href="<?= $deliverUrl ?>">Mark Delivered</a>
                            <?php else: ?>
                                <span class="status-text"><?= ucfirst($order['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="no-data">No orders found.</p>
<?php endif; ?>