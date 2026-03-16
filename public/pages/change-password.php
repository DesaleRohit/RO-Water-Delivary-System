<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../app/config/database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customerId = $_SESSION['customer_id'];

    $currentPassword = trim($_POST['current_password']);
    $newPassword     = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Fetch existing password
    $stmt = $conn->prepare("SELECT password FROM customers WHERE id = :id");
    $stmt->execute([':id' => $customerId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $message = "User not found.";
    } elseif ($currentPassword !== $user['password']) {
        $message = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "New passwords do not match.";
    } else {

        $update = $conn->prepare(
            "UPDATE customers SET password = :pwd WHERE id = :id"
        );

        $update->execute([
            ':pwd' => $newPassword,
            ':id'  => $customerId
        ]);

        $message = "Password updated successfully.";
    }
}
?>

<section class="change-password-section">

    <h2>Change Password</h2>

    <?php if ($message): ?>
        <?php
        // Determine the message type based on content or context
        $messageClass = 'error'; // default
        if (strpos($message, 'successfully') !== false) {
            $messageClass = 'success';
        }
        ?>
        <p class="message <?php echo $messageClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="post" class="change-password-form">

        <label>Current Password</label>
        <input type="password" name="current_password" pattern="\d{4}" maxlength="4" required>

        <label>New Password</label>
        <input type="password" name="new_password" pattern="\d{4}" maxlength="4" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" pattern="\d{4}" maxlength="4" required>

        <button type="submit">Update Password</button>

    </form>

    <a href="index.php?page=home" class="back-link">Back</a>

</section>