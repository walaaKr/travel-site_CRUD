<?php
/**
 * Admin: Update User
 * 
 * Form to edit an existing user
 * Only accessible to admin users
 */

session_start();
require_once('../../config/db.php');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header('Location: read.php');
    exit();
}

// Fetch user details
$user_query = "SELECT id, name, email, role FROM users WHERE id = " . intval($user_id);
$user_result = $conn->query($user_query);

if (!$user_result || $user_result->num_rows === 0) {
    header('Location: read.php');
    exit();
}

$user = $user_result->fetch_assoc();

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
    
    // Check if email already exists (for different user)
    if (empty($errors)) {
        $email_check = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "' AND id != " . intval($user_id));
        if ($email_check && $email_check->num_rows > 0) {
            $errors[] = 'Email already registered by another user.';
        }
    }
    
    // Update if no errors
    if (empty($errors)) {
        // Build update query (password only if provided)
        $update_query = "UPDATE users SET 
            name = '" . $conn->real_escape_string($name) . "',
            email = '" . $conn->real_escape_string($email) . "',
            role = '" . $conn->real_escape_string($role) . "'";
        
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            } else {
                $update_query .= ", password = '" . $conn->real_escape_string($password) . "'";
            }
        }
        
        $update_query .= " WHERE id = " . intval($user_id);
        
        if (empty($errors) && $conn->query($update_query)) {
            $success = true;
            // Refresh user data
            $user_result = $conn->query($user_query);
            $user = $user_result->fetch_assoc();
            header('refresh:2; url=read.php');
        } elseif (!empty($conn->error)) {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section style="padding: 4rem 2rem; min-height: 80vh;">
    <div style="max-width: 500px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 2rem;">Edit User</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                ✓ User updated successfully! Redirecting...
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
        
        <form method="POST" action="update.php?id=<?php echo htmlspecialchars($user_id); ?>" novalidate>
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required aria-label="Full name"
                       value="<?php echo htmlspecialchars($_POST['name'] ?? $user['name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required aria-label="Email address"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? $user['email']); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" aria-label="New password"
                       placeholder="At least 6 characters">
            </div>
            
            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" required aria-label="User role">
                    <option value="user" <?php echo ((isset($_POST['role']) ? $_POST['role'] : $user['role']) === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ((isset($_POST['role']) ? $_POST['role'] : $user['role']) === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Update User</button>
                <a href="read.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
