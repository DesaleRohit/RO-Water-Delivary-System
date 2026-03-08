<?php
require_once __DIR__ . "/../../app/config/database.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile   = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    // Fetch user by mobile (plain text password comparison)
    $stmt = $conn->prepare("SELECT id, name FROM customers WHERE mobile = :mobile AND password = :password");
    $stmt->execute([
        ':mobile'   => $mobile,
        ':password' => $password
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['customer_id']   = $user['id'];
        $_SESSION['customer_name'] = $user['name'];

        header("Location: index.php?page=home");
        exit;
    } else {
        $error = "Invalid mobile number or password";
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <h2>Welcome Back</h2>
        <p class="auth-subtitle">Login to place orders and track your deliveries</p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" class="auth-form order-form">
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="auth-footer">
            Don't have an account?
            <a href="index.php?page=register">Create Account</a>
        </p>
    </div>
</section>