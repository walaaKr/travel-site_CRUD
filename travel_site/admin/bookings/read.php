<?php
/**
 * Admin: View All Bookings
 * 
 * Table listing with edit/delete/create links
 * Only accessible to admin users
 */

session_start();
require_once('../../config/db.php');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit();
}

// Fetch all bookings with user and package names
$bookings_query = "SELECT b.id, u.name as user_name, p.title as package_title, b.booking_date, b.status, b.number_of_people, b.total_price 
                   FROM bookings b 
                   JOIN users u ON b.user_id = u.id 
                   JOIN packages p ON b.package_id = p.id 
                   ORDER BY b.id DESC";
$bookings_result = $conn->query($bookings_query);
$bookings = [];
if ($bookings_result) {
    while ($row = $bookings_result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section class="section" style="min-height: 80vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Manage Bookings</h1>
            <a href="create.php" class="btn btn-primary">+ New Booking</a>
        </div>
        
        <?php if (empty($bookings)): ?>
            <div style="background: white; padding: 2rem; border-radius: 1rem; text-align: center;">
                <p style="color: #9ca3af; margin-bottom: 1rem;">No bookings found.</p>
                <a href="create.php" class="btn btn-primary">Create your first booking</a>
            </div>
        <?php else: ?>
            <table role="grid" aria-label="Bookings list">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Package</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">People</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['package_title']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                            <td>
                                <span style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; background: <?php echo $booking['status'] === 'confirmed' ? '#d1fae5' : ($booking['status'] === 'pending' ? '#fef3c7' : '#fee2e2'); ?>; color: <?php echo $booking['status'] === 'confirmed' ? '#065f46' : ($booking['status'] === 'pending' ? '#92400e' : '#991b1b'); ?>;">
                                    <?php echo htmlspecialchars(ucfirst($booking['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($booking['number_of_people']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></td>
                            <td>
                                <a href="update.php?id=<?php echo htmlspecialchars($booking['id']); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Edit</a>
                                <a href="delete.php?id=<?php echo htmlspecialchars($booking['id']); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #dc2626;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
