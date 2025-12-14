<?php
/**
 * Navigation Bar - UPDATED
 * Includes links to main pages and login/signup
 * Admin links visible only to admin users
 * Theme toggle integrated in navbar
 */
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-container">
        <!-- Logo / Brand -->
        <a href="/travel_site/index.php" class="navbar-brand">
            <span class="brand-icon">✈️</span>
            <span class="brand-text">ViaNova</span>
        </a>
        
        <!-- Navigation Links -->
        <ul class="navbar-menu" role="menubar">
            <li role="none">
                <a href="/travel_site/index.php" role="menuitem" class="nav-link">Home</a>
            </li>
            <li role="none">
                <a href="/travel_site/pages/about.php" role="menuitem" class="nav-link">About</a>
            </li>
            <li role="none">
                <a href="/travel_site/pages/packages.php" role="menuitem" class="nav-link">Packages</a>
            </li>
            <li role="none">
                <a href="/travel_site/pages/map.php" role="menuitem" class="nav-link">Explore Map</a>
            </li>
          
            <li role="none">
                <a href="/travel_site/pages/contact.php" role="menuitem" class="nav-link">Contact</a>
            </li>
            
            <?php
            // Show admin area only to admin users
            if (isset($_SESSION['user']) && $_SESSION['role'] === 'admin') {
                echo '<li role="none" class="nav-divider">';
                echo '  <span class="nav-label">Admin</span>';
                echo '</li>';
                echo '<li role="none">';
                echo '  <a href="/travel_site/admin/packages/read.php" role="menuitem" class="nav-link nav-admin">Packages</a>';
                echo '</li>';
                echo '<li role="none">';
                echo '  <a href="/travel_site/admin/bookings/read.php" role="menuitem" class="nav-link nav-admin">Bookings</a>';
                echo '</li>';
                echo '<li role="none">';
                echo '  <a href="/travel_site/admin/users/read.php" role="menuitem" class="nav-link nav-admin">Users</a>';
                echo '</li>';
            }
            ?>
        </ul>
        
        <!-- Auth Links & Theme Toggle -->
        <div class="navbar-auth">
            <?php
            if (isset($_SESSION['user'])) {
                // User is logged in
                echo '<span class="user-greeting" aria-live="polite">Welcome, ' . htmlspecialchars($_SESSION['user']) . '</span>';
                echo '<a href="/travel_site/pages/logout.php" class="btn btn-secondary">Logout</a>';
            } else {
                // User is not logged in
                echo '<a href="/travel_site/pages/login.php" class="btn btn-secondary">Login</a>';
                echo '<a href="/travel_site/pages/signup.php" class="btn btn-primary">Sign Up</a>';
            }
            ?>
            
            <!-- Dark Mode/Theme Toggle Button -->
            <button id="themeToggle" class="theme-toggle" aria-label="Toggle theme" title="Click to change theme">
                🔥
            </button>
        </div>
    </div>
</nav>