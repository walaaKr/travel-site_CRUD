<?php
/**
 * Admin: Create New Booking
 * 
 * Form to create a new booking
 * Accessible to logged-in users (from packages page) or admin
 */

session_start();
require_once('../../config/db.php');

// Allow logged-in users to create bookings
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../pages/login.php');
    exit();
}

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Fetch users (admin only) and packages for dropdowns
$users = [];
if ($is_admin) {
    $users_query = "SELECT id, name, email FROM users ORDER BY name ASC";
    $users_result = $conn->query($users_query);
    if ($users_result) {
        while ($row = $users_result->fetch_assoc()) {
            $users[] = $row;
        }
    }
}

$packages_query = "SELECT id, title, price FROM packages ORDER BY title ASC";
$packages_result = $conn->query($packages_query);
$packages = [];
if ($packages_result) {
    while ($row = $packages_result->fetch_assoc()) {
        $packages[] = $row;
    }
}

// Pre-fill package_id if coming from packages page
$prefill_package_id = $_POST['package_id'] ?? $_GET['package_id'] ?? '';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // For regular users, use their own user_id
    $user_id = $is_admin ? trim($_POST['user_id'] ?? '') : $_SESSION['user_id'];
    $package_id = trim($_POST['package_id'] ?? '');
    $booking_date = trim($_POST['booking_date'] ?? '');
    $number_of_people = trim($_POST['number_of_people'] ?? '1');
    $status = $is_admin ? trim($_POST['status'] ?? 'pending') : 'pending';
    
    // Validation
    if ($is_admin && empty($user_id)) {
        $errors[] = 'Please select a user.';
    }
    
    if (empty($package_id)) {
        $errors[] = 'Please select a package.';
    }
    
    if (empty($booking_date)) {
        $errors[] = 'Booking date is required.';
    }
    
    if (empty($number_of_people) || !is_numeric($number_of_people) || $number_of_people < 1) {
        $errors[] = 'Please enter a valid number of people.';
    }
    
    // Calculate total price
    $total_price = 0;
    if (!empty($package_id)) {
        $price_query = "SELECT price FROM packages WHERE id = " . intval($package_id);
        $price_result = $conn->query($price_query);
        if ($price_result && $price_result->num_rows > 0) {
            $pkg = $price_result->fetch_assoc();
            $total_price = $pkg['price'] * intval($number_of_people);
        }
    }
    
    // Insert if no errors
    if (empty($errors)) {
        $insert_query = "INSERT INTO bookings (user_id, package_id, booking_date, number_of_people, total_price, status) VALUES (
            " . intval($user_id) . ",
            " . intval($package_id) . ",
            '" . $conn->real_escape_string($booking_date) . "',
            " . intval($number_of_people) . ",
            '" . $conn->real_escape_string($total_price) . "',
            '" . $conn->real_escape_string($status) . "'
        )";
        
        if ($conn->query($insert_query)) {
            $success = true;
            if ($is_admin) {
                header('refresh:2; url=read.php');
            } else {
                header('refresh:2; url=../../pages/packages.php');
            }
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
        <h1 style="text-align: center; margin-bottom: 2rem;"><?php echo $is_admin ? 'Create New Booking' : 'Book Package'; ?></h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                ✓ Booking created successfully! Redirecting...
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
            <?php if ($is_admin): ?>
            <div class="form-group">
                <label for="user_id">Customer *</label>
                <select id="user_id" name="user_id" required aria-label="Select customer">
                    <option value="">-- Select a customer --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['id']); ?>" <?php echo isset($_POST['user_id']) && $_POST['user_id'] == $user['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['name'] . ' (' . $user['email'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="package_id">Package *</label>
                <select id="package_id" name="package_id" required aria-label="Select package">
                    <option value="">-- Select a package --</option>
                    <?php foreach ($packages as $pkg): ?>
                        <option value="<?php echo htmlspecialchars($pkg['id']); ?>" data-price="<?php echo htmlspecialchars($pkg['price']); ?>" <?php echo ($prefill_package_id == $pkg['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pkg['title'] . ' ($' . number_format($pkg['price'], 2) . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="booking_date">Booking Date *</label>
                <input type="date" id="booking_date" name="booking_date" required aria-label="Booking date"
                       value="<?php echo htmlspecialchars($_POST['booking_date'] ?? date('Y-m-d')); ?>">
            </div>
            
            <div class="form-group">
                <label for="number_of_people">Number of People *</label>
                <input type="number" id="number_of_people" name="number_of_people" min="1" required aria-label="Number of people"
                       value="<?php echo htmlspecialchars($_POST['number_of_people'] ?? '1'); ?>">
            </div>
            
            <?php if ($is_admin): ?>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" aria-label="Booking status">
                    <option value="pending" <?php echo isset($_POST['status']) && $_POST['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="confirmed" <?php echo isset($_POST['status']) && $_POST['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="cancelled" <?php echo isset($_POST['status']) && $_POST['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <?php endif; ?>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Booking</button>
                <a href="<?php echo $is_admin ? 'read.php' : '../../pages/packages.php'; ?>" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
