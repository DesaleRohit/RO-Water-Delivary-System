<?php
require_once __DIR__ . "/../../app/config/database.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $mobile   = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    if ($name === '' || $mobile === '' || $password === '') {
        $error = "All fields are required";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters";
    } else {
        // Check if mobile already exists
        $check = $conn->prepare("SELECT id FROM customers WHERE mobile = :mobile");
        $check->execute([':mobile' => $mobile]);

        if ($check->rowCount() > 0) {
            $error = "Mobile number already registered";
        } else {
            // Store password as plain text (original behavior)
            $stmt = $conn->prepare("INSERT INTO customers (name, mobile, password) VALUES (:name, :mobile, :password)");
            $stmt->execute([
                ':name'     => $name,
                ':mobile'   => $mobile,
                ':password' => $password // plain text
            ]);

            header("Location: index.php?page=login");
            exit;
        }
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <h2>Create Account</h2>
        <p class="auth-subtitle">Register to start ordering RO water cans online</p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" class="auth-form order-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" placeholder="Enter mobile number" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create password" minlength="4" required>
                <small class="input-hint">Minimum 4 characters</small>
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>

        <p class="auth-footer">
            Already have an account?
            <a href="index.php?page=login">Login</a>
        </p>
    </div>
</section>