<?php
require_once __DIR__ . "/../../app/config/database.php";

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Fetch current admin's password (plain text as per original design)
    $stmt = $conn->prepare("SELECT password FROM admin_users WHERE username = :username");
    $stmt->execute([':username' => $_SESSION['admin_username']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $admin['password'] === $current) {
        if ($new === $confirm) {
            $update = $conn->prepare("UPDATE admin_users SET password = :password WHERE username = :username");
            if ($update->execute([':password' => $new, ':username' => $_SESSION['admin_username']])) {
                $message = "Password changed successfully.";
            } else {
                $error = "Failed to update password.";
            }
        } else {
            $error = "New password and confirm password do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<?php if ($message): ?>
    <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" action="index.php?page=change-password" class="change-password-form">

    <h2>Change Password</h2>
    <div class="form-group">
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>
    <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn-submit">Update Password</button>
</form>