<?php
/**
 * Admin: Delete Package
 * 
 * Confirmation form before deleting a package
 * Only accessible to admin users
 */

session_start();
require_once('../../config/db.php');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

$package_id = $_GET['id'] ?? null;

if (!$package_id) {
    header('Location: read.php');
    exit();
}

// Fetch package details
$package_query = "SELECT id, title FROM packages WHERE id = " . intval($package_id);
$package_result = $conn->query($package_query);

if (!$package_result || $package_result->num_rows === 0) {
    header('Location: read.php');
    exit();
}

$package = $package_result->fetch_assoc();

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? '';
    
    if ($confirm === 'yes') {
        // Delete package
        $delete_query = "DELETE FROM packages WHERE id = " . intval($package_id);
        
        if ($conn->query($delete_query)) {
            header('Location: read.php?success=deleted');
            exit();
        }
    }
    
    // If 'no' or error, stay on page or redirect
    header('Location: read.php');
    exit();
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section style="padding: 4rem 2rem; min-height: 80vh;">
    <div style="max-width: 500px; margin: 0 auto;">
        <div style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.1);">
            <h1 style="color: #dc2626; margin-bottom: 1rem;">Delete Package?</h1>
            
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #7f1d1d;">
                    <strong>Are you sure you want to delete this package?</strong><br>
                    <strong><?php echo htmlspecialchars($package['title']); ?></strong>
                </p>
                <p style="margin-top: 0.5rem; margin-bottom: 0; color: #991b1b; font-size: 0.9rem;">
                    This action cannot be undone. All bookings for this package will also be removed.
                </p>
            </div>
            
            <form method="POST" action="delete.php?id=<?php echo htmlspecialchars($package_id); ?>">
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" name="confirm" value="yes" class="btn btn-secondary" style="flex: 1; background-color: #dc2626;">
                        Yes, Delete It
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
