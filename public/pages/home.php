<?php
$isLoggedIn = isset($_SESSION['customer_id']);
?>

<header class="hero">
    <h1>RO Water Delivery System</h1>
    <p>
        Simple, reliable and affordable RO water can delivery
        designed for homes, offices and small businesses.
    </p>

    <div class="buttons">
        <?php if (!$isLoggedIn): ?>
            <a href="index.php?page=login">Login to Order</a>
            <a href="index.php?page=register" class="secondary">Create Account</a>
        <?php else: ?>
            <a href="index.php?page=order">Order Water Can</a>
            <a href="index.php?page=order-history" class="secondary">My Orders</a>
        <?php endif; ?>
    </div>
</header>

<!-- ABOUT -->
<section id="about">
    <h2>About the System</h2>
    <p>
        RO Water Delivery System is a web-based application that allows
        customers to place water can orders online and track their delivery
        status in real time.
    </p>

    <p class="highlight">
        The system simplifies order management for users and helps
        administrators manage daily deliveries efficiently.
    </p>
</section>

<!-- WHY CHOOSE -->
<section id="why" class="light">
    <h2>Why Use This Platform?</h2>

    <div class="features">
        <div class="box">
            <h3>💧 Safe Drinking Water</h3>
            <p>
                Water is purified using RO filtration to ensure safety
                and quality for daily consumption.
            </p>
        </div>

        <div class="box">
            <h3>📦 Easy Ordering</h3>
            <p>
                Place an order in seconds by selecting quantity and
                delivery date from your dashboard.
            </p>
        </div>

        <div class="box">
            <h3>📊 Order Tracking</h3>
            <p>
                Logged-in users can view order history, update or cancel
                pending orders anytime.
            </p>
        </div>
    </div>
</section>

<!-- WHO CAN USE -->
<section>
    <h2>Who Is This For?</h2>

    <div class="features">
        <div class="box">
            <h3>🏠 Residential Users</h3>
            <p>
                Ideal for families who need regular RO water delivery
                at their homes.
            </p>
        </div>

        <div class="box">
            <h3>🏢 Offices & Shops</h3>
            <p>
                Suitable for offices, shops and small workplaces
                requiring clean drinking water.
            </p>
        </div>

        <div class="box">
            <h3>🎉 Occasional Orders</h3>
            <p>
                Useful for events, meetings or temporary requirements
                with flexible order quantities.
            </p>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section id="how" class="light">
    <h2>How It Works</h2>

    <div class="steps">
        <div class="box">
            <h3>1️⃣ Login</h3>
            <p>
                Users create an account and log in to access
                the ordering system.
            </p>
        </div>

        <div class="box">
            <h3>2️⃣ Place Order</h3>
            <p>
                Select number of water cans, delivery date and
                delivery address.
            </p>
        </div>

        <div class="box">
            <h3>3️⃣ Delivery & Tracking</h3>
            <p>
                Admin processes the order and users can track
                delivery status from their account.
            </p>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section id="contact">
    <h2>Contact Information</h2>
    <p>
        📍 Location: Maharashtra, India<br>
        📞 Phone: +91 9XXXXXXXXX<br>
        📧 Email: roservice@example.com
    </p>
</section>