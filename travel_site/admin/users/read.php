<?php
/**
 * Admin: View All Users
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

// Fetch all users
$users_query = "SELECT id, name, email, role, created_at FROM users ORDER BY id DESC";
$users_result = $conn->query($users_query);
$users = [];
if ($users_result) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}

include_once('../../includes/header.php');
?>

<?php include_once('../../includes/navbar.php'); ?>

<section class="section" style="min-height: 80vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Manage Users</h1>
            <a href="create.php" class="btn btn-primary">+ Add User</a>
        </div>
        
        <?php if (empty($users)): ?>
            <div style="background: white; padding: 2rem; border-radius: 1rem; text-align: center;">
                <p style="color: #9ca3af; margin-bottom: 1rem;">No users found.</p>
                <a href="create.php" class="btn btn-primary">Create your first user</a>
            </div>
        <?php else: ?>
            <table role="grid" aria-label="Users list">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; background: <?php echo $user['role'] === 'admin' ? '#dbeafe' : '#f3f4f6'; ?>; color: <?php echo $user['role'] === 'admin' ? '#1e40af' : '#4b5563'; ?>; font-weight: 600;">
                                    <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(date('M d, Y', strtotime($user['created_at']))); ?></td>
                            <td>
                                <a href="update.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Edit</a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="delete.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #dc2626;">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
