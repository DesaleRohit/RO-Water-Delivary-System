<?php
require_once __DIR__ . "/../../app/config/database.php";

// ── Handle Delete ──────────────────────────────────────────────────────────
if (isset($_GET['delete_id'])) {
    $deleteId = filter_input(INPUT_GET, 'delete_id', FILTER_VALIDATE_INT);
    if ($deleteId) {
        // Orders are cascade-deleted via FK constraint (ON DELETE CASCADE)
        $stmt = $conn->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->execute([':id' => $deleteId]);
    }
    header("Location: index.php?page=customers&deleted=1");
    exit;
}

// ── Search / Sort ──────────────────────────────────────────────────────────
$searchName = $_GET['name']   ?? '';
$sortOrder  = $_GET['sort']   ?? 'newest';

// ── Build Query ────────────────────────────────────────────────────────────
$conditions = [];
$params     = [];

if (!empty($searchName)) {
    $conditions[] = "(c.name LIKE :name OR c.mobile LIKE :name OR c.address LIKE :name)";
    $params[':name'] = "%" . $searchName . "%";
}

$whereSQL  = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
$orderSQL  = ($sortOrder === 'oldest') ? "ORDER BY c.created_at ASC" : "ORDER BY c.created_at DESC";

// Total customers count
$countStmt = $conn->prepare("SELECT COUNT(*) FROM customers c $whereSQL");
$countStmt->execute($params);
$totalCustomers = $countStmt->fetchColumn();

// Fetch customers with order stats
$stmt = $conn->prepare("
    SELECT
        c.id,
        c.name,
        c.mobile,
        c.address,
        c.created_at,
        COUNT(o.id)                                              AS total_orders,
        COALESCE(SUM(o.quantity), 0)                             AS total_cans,
        COALESCE(SUM(CASE WHEN o.status = 'delivered' THEN o.quantity * 20 ELSE 0 END), 0) AS total_spent,
        SUM(CASE WHEN o.status = 'pending'   THEN 1 ELSE 0 END) AS pending_orders,
        SUM(CASE WHEN o.status = 'delivered' THEN 1 ELSE 0 END) AS delivered_orders
    FROM customers c
    LEFT JOIN orders o ON o.customer_id = c.id
    $whereSQL
    GROUP BY c.id
    $orderSQL
");
$stmt->execute($params);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ── Page Header ───────────────────────────────────────────────────── -->
<div class="customers-header">
    <h2>Customers</h2>
    <div class="customer-count">Total: <?= $totalCustomers ?></div>
</div>

<!-- ── Flash Messages ───────────────────────────────────────────────── -->
<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">Customer deleted successfully.</div>
<?php endif; ?>

<!-- ── Search & Sort Bar ─────────────────────────────────────────────── -->
<div class="search-bar">
    <form method="get" action="index.php">
        <input type="hidden" name="page" value="customers">
        <div class="search-group">
            <input
                type="text"
                name="name"
                placeholder="Search by name, mobile or address..."
                value="<?= htmlspecialchars($searchName) ?>"
                class="search-input"
            >
            <select name="sort" class="status-filter">
                <option value="newest" <?= $sortOrder === 'newest' ? 'selected' : '' ?>>Newest First</option>
                <option value="oldest" <?= $sortOrder === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
            </select>
            <button type="submit" class="search-btn">Search</button>
            <?php if (!empty($searchName) || $sortOrder !== 'newest'): ?>
                <a href="index.php?page=customers" class="clear-btn">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ── Customers Table ───────────────────────────────────────────────── -->
<?php if (!empty($customers)): ?>
    <div class="table-responsive">
        <table class="admin-table customers-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Total Orders</th>
                    <th>Cans Ordered</th>
                    <th>Total Spent</th>
                    <th>Pending</th>
                    <th>Delivered</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $i => $c): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <div class="customer-cell">
                            <div class="avatar-circle" style="background:<?= avatarColor($c['name']) ?>">
                                <?= strtoupper(mb_substr($c['name'], 0, 1)) ?>
                            </div>
                            <span class="customer-name-text"><?= htmlspecialchars($c['name']) ?></span>
                        </div>
                    </td>
                    <td data-label="Mobile"><?= htmlspecialchars($c['mobile']) ?></td>
                    <td data-label="Address" class="address-cell"><?= htmlspecialchars($c['address'] ?? '—') ?></td>
                    <td data-label="Total Orders">
                        <span class="stat-pill <?= $c['total_orders'] > 0 ? 'pill-blue' : 'pill-grey' ?>">
                            <?= $c['total_orders'] ?>
                        </span>
                    </td>
                    <td data-label="Cans"><?= $c['total_cans'] ?></td>
                    <td data-label="Total Spent">₹<?= number_format($c['total_spent']) ?></td>
                    <td data-label="Pending">
                        <span class="status-badge status-pending"><?= $c['pending_orders'] ?></span>
                    </td>
                    <td data-label="Delivered">
                        <span class="status-badge status-delivered"><?= $c['delivered_orders'] ?></span>
                    </td>
                    <td data-label="Joined"><?= date("d M Y", strtotime($c['created_at'])) ?></td>
                    <td data-label="Action">
                        <div class="action-group">
                            <a href="index.php?page=orders&customer_id=<?= $c['id'] ?>"
                               class="view-orders-btn" title="View Orders">
                               View Orders
                            </a>
                            <form method="get" action="index.php" class="delete-form"
                                  onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($c['name'])) ?> and all their orders?')">
                                <input type="hidden" name="page"      value="customers">
                                <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
                                <button type="submit" class="delete-btn" title="Delete Customer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="no-data customers-empty">
        <?= !empty($searchName) ? 'No customers match your search.' : 'No customers have registered yet.' ?>
    </div>
<?php endif; ?>

<?php
// Helper: deterministic color from first letter
function avatarColor(string $name): string {
    $colors = ['#2563eb','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#db2777'];
    return $colors[ord($name[0]) % count($colors)];
}
?>
