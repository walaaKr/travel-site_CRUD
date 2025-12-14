<?php
/**
 * Signup Page
 * 
 * Allows new users to create an account
 * Validates: name, email (unique), password
 * Stores data to users table with role='user'
 * Redirects to login after successful signup
 */

session_start();
require_once('../config/db.php');

$errors = [];
$success = false;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    
    // Check if email already exists (only if no validation errors yet)
    if (empty($errors)) {
        $email_check = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "'");
        if ($email_check && $email_check->num_rows > 0) {
            $errors[] = 'Email already registered. Please login or use a different email.';
        }
    }
    
    // Insert into database if no errors
    if (empty($errors)) {
        $insert_query = "INSERT INTO users (name, email, password, role) VALUES (
            '" . $conn->real_escape_string($name) . "',
            '" . $conn->real_escape_string($email) . "',
            '" . $conn->real_escape_string($password) . "',
            'user'
        )";
        
        if ($conn->query($insert_query)) {
            $success = true;
            // Redirect to login after 2 seconds
            header("refresh:2; url=login.php");
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

include_once('../includes/header.php');
?>

<?php include_once('../includes/navbar.php'); ?>

<section style="padding: 4rem 2rem; min-height: 80vh;">
    <div style="max-width: 500px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 0.5rem;">Create Account</h1>
        <p style="text-align: center; color: #6b7280; margin-bottom: 2rem;">Join ViaNova and start exploring amazing destinations</p>
        
        <?php if ($success): ?>
            <div class="success-message">
                ✓ Account created successfully! Redirecting to login...
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
        
        <form method="POST" action="signup.php" novalidate>
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required aria-label="Enter your full name" 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required aria-label="Enter your email address"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required aria-label="Create a password"
                       placeholder="At least 6 characters">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required aria-label="Confirm your password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Sign Up</button>
            
            <p style="text-align: center; color: #6b7280;">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>