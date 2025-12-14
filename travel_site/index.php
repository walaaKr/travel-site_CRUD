<?php
/**
 * Voyagely - Travel Explorer Homepage
 * 
 * Features:
 * - Hero section with background video
 * - Featured packages (3 latest from database)
 * - Call-to-action buttons
 * - Navigation via navbar
 * 
 * Database: travel_explorer
 * Tables used: packages
 */

session_start();

// Get absolute path to root directory for proper file resolution
$root_dir = dirname(__FILE__);

// Include database connection
$db_path = $root_dir . '/config/db.php';
if (!file_exists($db_path)) {
    die("ERROR: Database configuration file not found at: " . $db_path);
}
require_once($db_path);

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    die("ERROR: Database connection failed. Check your config/db.php and ensure MySQL is running.");
}

// Fetch featured packages (3 most recent)
$featured_query = "SELECT id, title, description, price, image FROM packages LIMIT 3";
$featured_result = $conn->query($featured_query);
$featured_packages = [];
if ($featured_result) {
    while ($row = $featured_result->fetch_assoc()) {
        $featured_packages[] = $row;
    }
}

// Include header (opens HTML document)
$header_path = $root_dir . '/includes/header.php';
include_once($header_path);
?>

<?php 
$navbar_path = $root_dir . '/includes/navbar.php';
include_once($navbar_path); 
?>

<!-- HERO SECTION -->
<section class="hero" role="banner" aria-label="Hero section">
    <!-- Background video (muted, autoplay, loop, playsinline) -->
<video class="hero-video"
       id="heroVideo"
       autoplay
       muted
       loop
       playsinline
       poster="/travel_site/assets/img/hero-poster.jpg"
       aria-hidden="true">
    <source src="/travel_site/assets/img/hero.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
</video>

    <!-- Fallback gradient (shown if video fails) -->
    <div class="hero-background-fallback"></div>
    
    <!-- Hero Content -->
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="gradient-text">Explore the World</span>
        </h1>
        <p class="hero-subtitle">Discover amazing destinations, book unforgettable adventures</p>
        <div class="hero-cta">
            <a href="/travel_site/pages/packages.php" class="btn btn-primary" aria-label="View all travel packages">
                View Packages
            </a>
           
        </div>
    </div>
</section>

<!-- FEATURED PACKAGES SECTION -->
<section class="section" role="region" aria-label="Featured travel packages">
    <div class="section-title">
        <h2 class="gradient-text">Featured Packages</h2>
        <p>Handpicked destinations waiting for you</p>
    </div>
    
    <div class="packages-grid">
        <?php foreach ($featured_packages as $package): ?>
            <article class="package-card" role="article">
                <!-- Package Image or Gradient Placeholder -->
                <?php if (!empty($package['image']) && file_exists('assets/img/' . $package['image'])): ?>
                    <img src="/travel_site/assets/img/<?php echo htmlspecialchars($package['image']); ?>" 
                         alt="<?php echo htmlspecialchars($package['title']); ?>"
                         class="package-image">
                <?php else: ?>
                    <div class="package-image" style="background: linear-gradient(135deg, #0EA5A4 0%, #F97316 100%);"></div>
                <?php endif; ?>
                
                <div class="package-body">
                    <h3 class="package-title"><?php echo htmlspecialchars($package['title']); ?></h3>
                    <div class="package-price">
                        $<?php echo htmlspecialchars(number_format($package['price'], 2)); ?>
                    </div>
                    <p class="package-description">
                        <?php echo htmlspecialchars(substr($package['description'], 0, 100) . '...'); ?>
                    </p>
                    <a href="/travel_site/pages/packages.php" class="btn btn-primary" aria-label="View package details">
                        View Details
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    
    <div style="text-align: center; margin-top: 3rem;">
        <a href="/travel_site/pages/packages.php" class="btn btn-secondary" aria-label="Browse all packages">
            Explore All Packages
        </a>
    </div>
</section>

<!-- CTA SECTION -->
<section class="section" style="background: linear-gradient(135deg, #0F172A 0%, #0B1220 100%); color: white; border-radius: 1rem; margin: 4rem 2rem;">
    <div style="text-align: center;">
        <h2 style="color: white; margin-bottom: 1rem;">Ready to Start Your Adventure?</h2>
        <p style="font-size: 1.1rem; margin-bottom: 2rem; color: #d1d5db;">
            Sign up today and get exclusive access to amazing travel deals.
        </p>
        <a href="/travel_site/pages/signup.php" class="btn btn-primary" aria-label="Create a new account">
            Sign Up Now
        </a>
    </div>
</section>
<!-- Footer (animated on scroll) -->
<?php 
$footer_path = $root_dir . '/includes/footer.php';
include_once($footer_path); 
?>
<script>
  const heroVideo = document.getElementById("heroVideo");

  heroVideo.addEventListener("loadedmetadata", () => {
      heroVideo.playbackRate = 0.4; // slow & cinematic
  });
</script>

<script src="./assets/theme-toggle.js"></script>
