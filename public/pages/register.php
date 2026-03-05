<?php
require_once __DIR__ . "/../../app/config/database.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $mobile   = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    if ($name === '' || $mobile === '' || $password === '') {
        $error = "All fields are required";
    } else {
        // Check if mobile already exists
        $check = $conn->prepare(
            "SELECT id FROM customers WHERE mobile = :mobile"
        );
        $check->execute([':mobile' => $mobile]);

        if ($check->rowCount() > 0) {
            $error = "Mobile number already registered";
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO customers (name, mobile, password)
                 VALUES (:name, :mobile, :password)"
            );
            $stmt->execute([
                ':name'     => $name,
                ':mobile'   => $mobile,
                ':password' => $password
            ]);

            header("Location: index.php?page=login");
            exit;
        }
    }
}
?>

<section class="auth-section">

    <h2>Create Account</h2>
    <p class="auth-subtitle">
        Register to start ordering RO water cans online.
    </p>

    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="auth-form order-form">

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your name" required>

        <label>Mobile Number</label>
        <input type="text" name="mobile" placeholder="Enter mobile number" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Create password" maxlength="4" required>

        <button type="submit">Register</button>
    </form>

    <p class="auth-footer">
        Already have an account?
        <a href="index.php?page=login">Login</a>
    </p>

</section>