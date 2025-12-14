<?php
/**
 * Admin: Create New User
 * 
 * Form to create a new user or admin
 * Only accessible to admin users
 */

session_start();
require_once('../../config/db.php');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? 'user');
    
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
        $errors[] = 'Password must be at least 6 characters.';
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $email_check = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "'");
        if ($email_check && $email_check->num_rows > 0) {
            $errors[] = 'Email already registered.';
        }
    }
    
    // Insert into database if no errors
    if (empty($errors)) {
        $insert_query = "INSERT INTO users (name, email, password, role) VALUES (
            '" . $conn->real_escape_string($name) . "',
            '" . $conn->real_escape_string($email) . "',
            '" . $conn->real_escape_string($password) . "',
            '" . $conn->real_escape_string($role) . "'
        )";
        
        if ($conn->query($insert_query)) {
            $success = true;
            header('refresh:2; url=read.php');
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section style="padding: 4rem 2rem; min-height: 80vh;">
    <div style="max-width: 500px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 2rem;">Add New User</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                ✓ User created successfully! Redirecting...
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
        
        <form method="POST" action="create.php" novalidate>
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required aria-label="Full name"
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required aria-label="Email address"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required aria-label="Password"
                       placeholder="At least 6 characters">
            </div>
            
            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" required aria-label="User role">
                    <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create User</button>
                <a href="read.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
