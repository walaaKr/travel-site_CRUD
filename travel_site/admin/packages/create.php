<?php
/**
 * Admin: Create New Package
 * 
 * Form to create a new travel package
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
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $duration_days = trim($_POST['duration_days'] ?? '');
    $image = trim($_POST['image'] ?? '');
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }
    
    if (empty($price)) {
        $errors[] = 'Price is required.';
    } elseif (!is_numeric($price)) {
        $errors[] = 'Price must be a valid number.';
    }
    
    // Insert into database if no errors
    if (empty($errors)) {
        $insert_query = "INSERT INTO packages (title, description, price, image, destination, duration_days) VALUES (
            '" . $conn->real_escape_string($title) . "',
            '" . $conn->real_escape_string($description) . "',
            '" . $conn->real_escape_string($price) . "',
            '" . $conn->real_escape_string($image) . "',
            '" . $conn->real_escape_string($destination) . "',
            '" . $conn->real_escape_string($duration_days) . "'
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
    <div style="max-width: 700px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 2rem;">Create New Package</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                ✓ Package created successfully! Redirecting...
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
                <label for="title">Package Title *</label>
                <input type="text" id="title" name="title" required aria-label="Package title"
                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required aria-label="Package description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price ($) *</label>
                <input type="number" id="price" name="price" step="0.01" required aria-label="Package price"
                       value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="destination">Destination</label>
                <input type="text" id="destination" name="destination" aria-label="Destination"
                       value="<?php echo htmlspecialchars($_POST['destination'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="duration_days">Duration (Days)</label>
                <input type="number" id="duration_days" name="duration_days" aria-label="Duration in days"
                       value="<?php echo htmlspecialchars($_POST['duration_days'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="image">Image Filename (stored in /assets/img/)</label>
                <input type="text" id="image" name="image" placeholder="e.g., sahara.jpg" aria-label="Image filename"
                       value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>">
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Package</button>
                <a href="read.php" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('../../includes/footer.php'); ?>
