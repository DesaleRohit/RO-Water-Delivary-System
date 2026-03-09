<?php
require_once __DIR__ . "/../../app/config/database.php";

$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $mobile = trim($_POST["mobile"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    $stmt = $conn->prepare(
        "INSERT INTO contact_messages (name, mobile, subject, message) 
         VALUES (:name, :mobile, :subject, :message)"
    );

    $stmt->execute([
        ':name' => $name,
        ':mobile' => $mobile,
        ':subject' => $subject,
        ':message' => $message
    ]);

    $success = "Message sent successfully!";
}
?>

<section class="contact-form order-form">
    <div class="container">
        <h2>Contact Us</h2>

        <?php if ($success): ?>
            <p class="success-msg" id="successMsg"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" id="contactForm">
            <input type="text" name="name" placeholder="Your Name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            <input type="text" name="mobile" placeholder="Mobile Number" required value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
            <input type="text" name="subject" placeholder="Subject" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
            <textarea name="message" placeholder="Your Message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</section>