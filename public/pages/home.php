<?php
$isLoggedIn = isset($_SESSION['customer_id']);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Pure Water,<br>Delivered to Your Doorstep</h1>
        <p class="hero-subtitle">
            Fresh, purified RO water cans delivered on time, every time.
            Simple online ordering for homes and offices.
        </p>
        <div class="hero-buttons">
            <?php if (!$isLoggedIn): ?>
                <a href="index.php?page=login" class="btn btn-primary">Login to Order</a>
                <a href="index.php?page=register" class="btn btn-outline">Create Account</a>
            <?php else: ?>
                <a href="index.php?page=order" class="btn btn-primary">Order Water Can</a>
                <a href="index.php?page=order-history" class="btn btn-outline">My Orders</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Stats Section with icons -->
<section class="stats">
    <div class="stat-item">
        <i class="fas fa-truck"></i>
        <span class="stat-number" data-target="10000">10K+</span>
        <span class="stat-label">Deliveries</span>
    </div>
    <div class="stat-item">
        <i class="fas fa-smile"></i>
        <span class="stat-number" data-target="5000">5K+</span>
        <span class="stat-label">Happy Customers</span>
    </div>
    <div class="stat-item">
        <i class="fas fa-headset"></i>
        <span class="stat-number">24/7</span>
        <span class="stat-label">Support</span>
    </div>
    <div class="stat-item">
        <i class="fas fa-check-circle"></i>
        <span class="stat-number">100%</span>
        <span class="stat-label">Safe Water</span>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about">
    <div class="container">
        <h2 class="section-title">About the System</h2>
        <p class="section-description">
            RO Water Delivery System is a web-based platform that connects customers with
            reliable RO water can delivery services. Order online, track in real-time,
            and enjoy fresh water at your convenience.
        </p>
    </div>
</section>

<!-- Why Choose Us with icons -->
<section id="why" class="why-us">
    <div class="container">
        <h2 class="section-title">Why Choose Us?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-water"></i></div>
                <h3>Safe Drinking Water</h3>
                <p>Multi-stage RO purification ensures the highest quality water for your health.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Easy Ordering</h3>
                <p>Place orders in seconds via our web app. Schedule deliveries at your convenience.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
                <h3>Real-Time Tracking</h3>
                <p>Track your order status from confirmation to delivery with live updates.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-credit-card"></i></div>
                <h3>Flexible Payment</h3>
                <p>Pay online or via cash on delivery - whatever suits you best.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works with gradient numbers -->
<section id="how" class="how-it-works">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Create Account</h3>
                <p>Sign up with your details and verify your mobile number.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Place Order</h3>
                <p>Select quantity, delivery date, and address. Confirm your order.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Get Delivery</h3>
                <p>Our partner delivers the cans to your doorstep on the scheduled date.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Enjoy & Repeat</h3>
                <p>Track, reorder, or manage your subscriptions from your dashboard.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials with quote icon -->
<section class="testimonials">
    <div class="container">
        <h2 class="section-title">What Our Customers Say</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <i class="fas fa-quote-left quote-icon"></i>
                <p>"Absolutely hassle-free service. The water quality is excellent and deliveries are always on time."</p>
                <div class="customer">
                    <strong>- Priya S.</strong>
                    <span>Shahada</span>
                </div>
            </div>
            <div class="testimonial-card">
                <i class="fas fa-quote-left quote-icon"></i>
                <p>"Great for our office. We order 20 cans weekly and the online tracking makes planning easy."</p>
                <div class="customer">
                    <strong>- Rajesh K.</strong>
                    <span>Lonkheda</span>
                </div>
            </div>
            <div class="testimonial-card">
                <i class="fas fa-quote-left quote-icon"></i>
                <p>"The customer support is fantastic. They resolved a delivery issue within minutes."</p>
                <div class="customer">
                    <strong>- Anjali M.</strong>
                    <span>Lambola</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section with icons -->
<section id="contact" class="contact">
    <div class="container">
        <h2 class="section-title">Get in Touch</h2>
        <div class="contact-details">
            <p><i class="fas fa-map-marker-alt"></i> Maharashtra, India</p>
            <p><i class="fas fa-phone-alt"></i> +91 98765 43210</p>
            <p><i class="fas fa-envelope"></i> roservice@example.com</p>
        </div>
    </div>
</section>