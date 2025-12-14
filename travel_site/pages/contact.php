<?php
/**
 * Contact Page
 * 
 * Contact form that submits to contact_messages table
 * Server-side validation
 */

session_start();
require_once('../config/db.php');

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required.';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required.';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters long.';
    }
    
    // Insert into database if no errors
    if (empty($errors)) {
        $insert_query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (
            '" . $conn->real_escape_string($name) . "',
            '" . $conn->real_escape_string($email) . "',
            '" . $conn->real_escape_string($subject) . "',
            '" . $conn->real_escape_string($message) . "'
        )";
        
        if ($conn->query($insert_query)) {
            $success = true;
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

include_once('../includes/header.php');
?>

<?php include_once('../includes/navbar.php'); ?>

<section class="contact-container">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1>Contact Us</h1>
        <p style="color: #6b7280; font-size: 1.1rem;">We'd love to hear from you. Get in touch with any questions or inquiries.</p>
    </div>
    
    <?php if ($success): ?>
        <div class="success-message">
            ✓ Your message has been sent successfully! We'll get back to you soon.
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 4px solid #dc2626;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top: 0.5rem;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
        <!-- Contact Form -->
        <form method="POST" action="contact.php" novalidate>
            <div class="form-group">
                <label for="name">Your Name *</label>
                <input type="text" id="name" name="name" required aria-label="Enter your name"
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required aria-label="Enter your email address"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" required aria-label="Enter subject"
                       value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" required aria-label="Enter your message"
                          placeholder="Please provide details about your inquiry..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
        </form>
        
        <!-- Contact Info -->
        <div class="contact-info">
            <h2>Get in Touch</h2>
            
            <div class="contact-item">
                <strong>📧 Email</strong>
                <p>support@ViaNova.com</p>
            </div>
            
            <div class="contact-item">
                <strong>📱 Phone</strong>
                <p>+213 0553562069</p>
            </div>
            
            <div class="contact-item">
                <strong>📍 Address</strong>
                <p>22 Travel Street<br>Adventure City, AC 12345<br>United States</p>
            </div>
            
            <div class="contact-item">
                <strong>⏰ Hours</strong>
                <p>6 days a week<br>Sunday: 10 AM - 4 PM<br>friday: Closed</p>
            </div>
            
            <div style="margin-top: 2rem; padding: 1.5rem; background: #f0f9f9; border-radius: 0.5rem; border-left: 4px solid var(--color-accent-1);">
                <p style="margin: 0;"><strong>💬 Quick Help:</strong> Try our Chat Bot for instant answers about packages and bookings!</p>
            </div>
        </div>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>