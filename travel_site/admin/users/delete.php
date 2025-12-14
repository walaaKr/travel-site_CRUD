<?php
/**
 * Admin: Delete User
 * 
 * Confirmation form before deleting a user
 * Only accessible to admin users
 * Cannot delete yourself
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

// Prevent deleting yourself
if ($user_id == $_SESSION['user_id']) {
    header('Location: read.php?error=self');
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

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? '';
    
    if ($confirm === 'yes') {
        // Delete user
        $delete_query = "DELETE FROM users WHERE id = " . intval($user_id);
        
        if ($conn->query($delete_query)) {
            header('Location: read.php?success=deleted');
            exit();
        }
    }
    
    // If 'no' or error, redirect back
    header('Location: read.php');
    exit();
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section style="padding: 4rem 2rem; min-height: 80vh;">
    <div style="max-width: 500px; margin: 0 auto;">
        <div style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.1);">
            <h1 style="color: #dc2626; margin-bottom: 1rem;">Delete User?</h1>
            
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #7f1d1d;">
                    <strong>Are you sure you want to delete this user?</strong><br><br>
                    <strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
                    <strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                </p>
                <p style="margin-top: 0.5rem; margin-bottom: 0; color: #991b1b; font-size: 0.9rem;">
                    This action cannot be undone. All bookings by this user will also be removed.
                </p>
            </div>
            
            <form method="POST" action="delete.php?id=<?php echo htmlspecialchars($user_id); ?>">
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" name="confirm" value="yes" class="btn btn-secondary" style="flex: 1; background-color: #dc2626;">
                        Yes, Delete User
                    </button>
                    <a href="read.php" class="btn btn-secondary" style="flex: 1; text-align: center;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
