<?php
/**
 * Admin: View All Packages
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

// Fetch all packages
$packages_query = "SELECT id, title, price, destination, duration_days FROM packages ORDER BY id DESC";
$packages_result = $conn->query($packages_query);
$packages = [];
if ($packages_result) {
    while ($row = $packages_result->fetch_assoc()) {
        $packages[] = $row;
    }
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section class="section" style="min-height: 80vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Manage Packages</h1>
            <a href="create.php" class="btn btn-primary">+ Create Package</a>
        </div>
        
        <?php if (empty($packages)): ?>
            <div style="background: white; padding: 2rem; border-radius: 1rem; text-align: center;">
                <p style="color: #9ca3af; margin-bottom: 1rem;">No packages found.</p>
                <a href="create.php" class="btn btn-primary">Create your first package</a>
            </div>
        <?php else: ?>
            <table role="grid" aria-label="Packages list">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Destination</th>
                        <th scope="col">Price</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $pkg): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($pkg['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($pkg['destination'] ?? '—'); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($pkg['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($pkg['duration_days'] ?? '—'); ?> days</td>
                            <td>
                                <a href="update.php?id=<?php echo htmlspecialchars($pkg['id']); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Edit</a>
                                <a href="delete.php?id=<?php echo htmlspecialchars($pkg['id']); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #dc2626;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
