<?php
require_once __DIR__ . "/../../app/config/database.php";

$error = "";
$success = "";
$showResetForm = false;
$mobile = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 2: Reset password
    if (isset($_POST['reset_password'])) {
        $mobile = trim($_POST['mobile']);
        $newPassword = trim($_POST['new_password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if ($newPassword === '' || $confirmPassword === '') {
            $error = "Please fill in both password fields.";
        } elseif (strlen($newPassword) < 4) {
            $error = "Password must be at least 4 characters.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "Passwords do not match.";
        } else {
            // Update password in database
            $stmt = $conn->prepare("UPDATE customers SET password = :password WHERE mobile = :mobile");
            $stmt->execute([
                ':password' => $newPassword,
                ':mobile'   => $mobile
            ]);

            if ($stmt->rowCount() > 0) {
                $success = "Password updated successfully! You can now login with your new password.";
                // Redirect to login after a short delay or show link
            } else {
                $error = "Failed to update password. Please try again.";
            }
        }
        // If there was an error, show the reset form again with the mobile
        if ($error) {
            $showResetForm = true;
        }
    }
    // Step 1: Check mobile
    elseif (isset($_POST['check_mobile'])) {
        $mobile = trim($_POST['mobile']);
        if ($mobile === '') {
            $error = "Please enter your mobile number.";
        } else {
            // Check if mobile exists
            $stmt = $conn->prepare("SELECT id FROM customers WHERE mobile = :mobile");
            $stmt->execute([':mobile' => $mobile]);
            if ($stmt->rowCount() > 0) {
                $showResetForm = true; // Show the password reset form
            } else {
                $error = "Mobile number not found.";
            }
        }
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <h2><?= $showResetForm ? 'Reset Password' : 'Forgot Password' ?></h2>
        <p class="auth-subtitle">
            <?= $showResetForm ? 'Enter your new password below.' : 'Enter your mobile number to reset your password.' ?>
        </p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <p class="auth-footer">
                <a href="index.php?page=login">Go to Login</a>
            </p>
        <?php else: ?>
            <?php if (!$showResetForm): ?>
                <!-- Step 1: Mobile number form -->
                <form method="post" class="auth-form order-form">
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" placeholder="Enter registered mobile number" value="<?= htmlspecialchars($mobile) ?>" required>
                    </div>
                    <button type="submit" name="check_mobile" class="btn-submit">Continue</button>
                </form>
            <?php else: ?>
                <!-- Step 2: Reset password form -->
                <form method="post" class="auth-form order-form">
                    <input type="hidden" name="mobile" value="<?= htmlspecialchars($mobile) ?>">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter new password" pattern="\d{4}" maxlength="4" required>
                        <small class="input-hint">Minimum 4 characters</small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" pattern="\d{4}" maxlength="4" required>
                    </div>
                    <button type="submit" name="reset_password" class="btn-submit">Update Password</button>
                </form>
            <?php endif; ?>

            <p class="auth-footer">
                <a href="index.php?page=login">Back to Login</a>
            </p>
        <?php endif; ?>
    </div>
</section>