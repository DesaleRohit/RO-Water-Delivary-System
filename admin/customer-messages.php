<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';

require_once __DIR__ . "/../app/config/database.php";

// Fetch messages
$stmt = $conn->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$messageCount = count($messages);
?>

<main class="admin-main">
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="no-data">No messages found.</div>
    <?php endif; ?>
</main>
