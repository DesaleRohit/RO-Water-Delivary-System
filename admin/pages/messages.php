<?php
require_once __DIR__ . "/../../app/config/database.php";

// Handle message deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
    if ($deleteId) {
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = :id");
        $stmt->execute([':id' => $deleteId]);
    }
    // Redirect to avoid form resubmission
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Fetch messages
$stmt = $conn->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$messageCount = count($messages);
?>

<div class="messages-header">
    <h2>Customer Messages</h2>
    <div class="message-count">Total: <?php echo $messageCount; ?></div>
</div>

<?php if ($messages): ?>
    <div class="table-responsive">
        <table class="admin-table messages-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                    <tr class="message-row">
                        <td data-label="Name"><?php echo htmlspecialchars($m['name']); ?></td>
                        <td data-label="Mobile"><?php echo htmlspecialchars($m['mobile']); ?></td>
                        <td data-label="Subject"><?php echo htmlspecialchars($m['subject']); ?></td>
                        <td data-label="Message" class="message-cell">
                            <span class="message-preview"><?php echo htmlspecialchars(substr($m['message'], 0, 50)) . (strlen($m['message']) > 50 ? '...' : ''); ?></span>
                            <span class="message-full"><?php echo htmlspecialchars($m['message']); ?></span>
                        </td>
                        <td data-label="Date"><?php echo date("d M Y", strtotime($m['created_at'])); ?></td>
                        <td data-label="Action">
                            <form method="post" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                <input type="hidden" name="delete_id" value="<?php echo $m['id']; ?>">
                                <button type="submit" class="delete-btn" title="Delete message">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="no-data">No messages found.</div>
<?php endif; ?>