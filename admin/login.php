<?php
session_start();
require_once __DIR__ . "/../app/config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT * FROM admin_users WHERE username = :username AND password = :password"
    );

    $stmt->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | RO Water Delivery</title>

    <link rel="stylesheet" href="../public/assets/css/admin-login.css">
</head>

<body>

    <div class="login-card">
        <h2>Admin Login</h2>
        <p class="subtitle">RO Water Delivery System</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <div class="login-footer">
            © <?php echo date("Y"); ?> RO Water Delivery
        </div>
    </div>

    <script src="../public/assets/js/form-validation.js"></script>

</body>

</html>