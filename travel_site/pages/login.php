<?php
/**
 * FIXED LOGIN PAGE (safe, no DB leak, no warnings)
 */

ini_set('display_errors', 0);   // hide errors from frontend
ini_set('log_errors', 1);       // log errors instead
error_reporting(E_ALL);

session_start();
require_once('../config/db.php');

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: ../admin/packages/read.php');
    } else {
        header('Location: ../index.php');
    }
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = 'Email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {

        // Prepare statement (prevents SQL errors leaking)
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Student-level plain text comparison
                if ($password === $user['password']) {

                    $_SESSION['user'] = $user['name'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header('Location: ../admin/packages/read.php');
                    } else {
                        header('Location: ../index.php');
                    }
                    exit();

                } else {
                    $errors[] = 'Invalid email or password.';
                }
            } else {
                $errors[] = 'Invalid email or password.';
            }

            $stmt->close();
        } else {
            // If prepare() fails, do not expose DB info
            $errors[] = 'An internal error occurred. Please try again later.';
        }
    }
}

include_once('../includes/header.php');
include_once('../includes/navbar.php');
?>


<section style="padding: 4rem 2rem; min-height: 80vh;">
    <div style="max-width: 500px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 0.5rem;">Login</h1>
        <p style="text-align: center; color: #6b7280; margin-bottom: 2rem;">Welcome back to ViaNova</p>
        
        <?php if (!empty($errors)): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 4px solid #dc2626;">
                <strong>Login failed:</strong>
                <ul style="margin-top: 0.5rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Demo Credentials Box -->
       
        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required aria-label="Enter your email address"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required aria-label="Enter your password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Login</button>
            
            <p style="text-align: center; color: #6b7280;">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </p>
        </form>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>