<?php
/**
 * Admin: Update Booking
 * 
 * Form to edit an existing booking (mainly status changes)
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
$booking_query = "SELECT id, user_id, package_id, booking_date, number_of_people, total_price, status FROM bookings WHERE id = " . intval($booking_id);
$booking_result = $conn->query($booking_query);

if (!$booking_result || $booking_result->num_rows === 0) {
    header('Location: read.php');
    exit();
}

$booking = $booking_result->fetch_assoc();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = trim($_POST['booking_date'] ?? '');
    $number_of_people = trim($_POST['number_of_people'] ?? '');
    $total_price = trim($_POST['total_price'] ?? '');
    $status = trim($_POST['status'] ?? 'pending');
    
    // Validation
    if (empty($booking_date)) {
        $errors[] = 'Booking date is required.';
    }
    
    if (empty($number_of_people) || !is_numeric($number_of_people) || $number_of_people < 1) {
        $errors[] = 'Please enter a valid number of people.';
    }
    
    if (empty($total_price) || !is_numeric($total_price)) {
        $errors[] = 'Please enter a valid total price.';
    }
    
    // Update if no errors
    if (empty($errors)) {
        $update_query = "UPDATE bookings SET 
            booking_date = '" . $conn->real_escape_string($booking_date) . "',
            number_of_people = " . intval($number_of_people) . ",
            total_price = '" . $conn->real_escape_string($total_price) . "',
            status = '" . $conn->real_escape_string($status) . "'
            WHERE id = " . intval($booking_id);
        
        if ($conn->query($update_query)) {
            $success = true;
            // Refresh booking data
            $booking_result = $conn->query($booking_query);
            $booking = $booking_result->fetch_assoc();
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
    <div style="max-width: 700px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 2rem;">Edit Booking</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                ✓ Booking updated successfully! Redirecting...
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
        
        <form method="POST" action="update.php?id=<?php echo htmlspecialchars($booking_id); ?>" novalidate>
            <div class="form-group">
                <label for="booking_date">Booking Date *</label>
                <input type="date" id="booking_date" name="booking_date" required aria-label="Booking date"
                       value="<?php echo htmlspecialchars($_POST['booking_date'] ?? $booking['booking_date']); ?>">
            </div>
            
            <div class="form-group">
                <label for="number_of_people">Number of People *</label>
                <input type="number" id="number_of_people" name="number_of_people" min="1" required aria-label="Number of people"
                       value="<?php echo htmlspecialchars($_POST['number_of_people'] ?? $booking['number_of_people']); ?>">
            </div>
            
            <div class="form-group">
                <label for="total_price">Total Price ($) *</label>
                <input type="number" id="total_price" name="total_price" step="0.01" required aria-label="Total price"
                       value="<?php echo htmlspecialchars($_POST['total_price'] ?? $booking['total_price']); ?>">
            </div>
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required aria-label="Booking status">
                    <option value="pending" <?php echo (isset($_POST['status']) ? $_POST['status'] : $booking['status']) === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="confirmed" <?php echo (isset($_POST['status']) ? $_POST['status'] : $booking['status']) === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="cancelled" <?php echo (isset($_POST['status']) ? $_POST['status'] : $booking['status']) === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Update Booking</button>
                <a href="read.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
