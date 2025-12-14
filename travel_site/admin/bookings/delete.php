<?php
/**
 * Admin: Delete Booking
 * 
 * Confirmation form before deleting a booking
 */

session_start();
require_once('../../config/db.php');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

$booking_id = $_GET['id'] ?? null;

if (!$booking_id) {
    header('Location: read.php');
    exit();
}

// Fetch booking details
$booking_query = "SELECT b.id, u.name as user_name, p.title as package_title FROM bookings b 
                   JOIN users u ON b.user_id = u.id 
                   JOIN packages p ON b.package_id = p.id 
                   WHERE b.id = " . intval($booking_id);
$booking_result = $conn->query($booking_query);

if (!$booking_result || $booking_result->num_rows === 0) {
    header('Location: read.php');
    exit();
}

$booking = $booking_result->fetch_assoc();

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? '';
    
    if ($confirm === 'yes') {
        // Delete booking
        $delete_query = "DELETE FROM bookings WHERE id = " . intval($booking_id);
        
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
            <h1 style="color: #dc2626; margin-bottom: 1rem;">Delete Booking?</h1>
            
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #7f1d1d;">
                    <strong>Are you sure you want to delete this booking?</strong><br>
                    <strong><?php echo htmlspecialchars($booking['user_name']); ?></strong> - <?php echo htmlspecialchars($booking['package_title']); ?>
                </p>
                <p style="margin-top: 0.5rem; margin-bottom: 0; color: #991b1b; font-size: 0.9rem;">
                    This action cannot be undone.
                </p>
            </div>
            
            <form method="POST" action="delete.php?id=<?php echo htmlspecialchars($booking_id); ?>">
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
