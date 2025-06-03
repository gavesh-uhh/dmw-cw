<?php
include "../includes/utility.php";
session_start();
$page_title = 'Fried Frenzy • Contact Us';
$page_css = '../css/contact.css';
include '../includes/header.php';
include '../includes/db_connect.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $content = trim($_POST['content']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (empty($content)) {
        $error = "Please enter your feedback message.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO feedback (email, content) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("ss", $email, $content);
            
            if ($stmt->execute()) {
                $message = "Thank you for your feedback! We'll get back to you soon.";
                // Clear the form
                $email = '';
                $content = '';
            } else {
                throw new Exception("Failed to submit feedback: " . $stmt->error);
            }
        } catch (Exception $e) {
            $error = "An error occurred. Please try again later.";
            error_log("Feedback error: " . $e->getMessage());
        }
    }
}
?>

<body>
    <header class="header">
        <img src="../assets/logo-header.png" id="nav_logo">
        <div class="header-right">
            <nav class="option-btns">
                <a href="<?php echo getRootPath(''); ?>"><i class="bi bi-house"></i> Home</a>
                <a href="<?php echo getRootPath('products'); ?>" class="active"><i class="bi bi-search"></i>Browse</a>
            </nav>

            <nav class="nav-btns">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?php echo getRootPath('orders'); ?>">My Orders</a>
                    <a class="icon" href="<?php echo getRootPath('auth/logout'); ?>"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="<?php echo getRootPath('auth/login'); ?>">Login</a>
                    <a href="<?php echo getRootPath('auth/register'); ?>">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="contact-container">
        <div class="contact-content">
            <h1>Contact Us</h1>
            <p class="contact-intro">We'd love to hear from you! Send us your feedback, suggestions, or any questions you might have.</p>

            <div class="contact-details">
                <div class="contact-info">
                    <div class="info-item">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <h3>Our Location</h3>
                            <p>11A Vidya Mawatha, Colombo 00700</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <h3>Phone Number</h3>
                            <p>+94 11 234 5678</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-envelope"></i>
                        <div>
                            <h3>Email Address</h3>
                            <p>info@friedfrenzy.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-clock"></i>
                        <div>
                            <h3>Opening Hours</h3>
                            <p>Monday - Sunday: 10:00 AM - 10:00 PM</p>
                        </div>
                    </div>
                </div>

                <div class="map-container">
                    <div style="width: 100%">
                        <iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                            src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=11a%20Vidya%20Mawatha,%20Colombo%2000700+(Fried%20Frenzy)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                        </iframe>
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="success-message">
                    <i class="bi bi-check-circle"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="feedback-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required
                        value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                        placeholder="Enter your email address">
                </div>

                <div class="form-group">
                    <label for="content">Your Message</label>
                    <textarea id="content" name="content" required rows="5"
                        placeholder="Tell us what's on your mind..."><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="bi bi-send"></i> Send Feedback
                </button>
            </form>
        </div>
    </div>
</body>
</html> 