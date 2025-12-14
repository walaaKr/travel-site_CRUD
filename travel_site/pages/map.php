<?php
/**
 * Map Page - Explore Destinations
 * 
 * Displays all places from database with:
 * - Name, category, description
 * - OpenStreetMap embed link
 * - Card-based layout with gradients
 * 
 * Database: places table
 */

session_start();
require_once('../config/db.php');

// Fetch all places
$places_query = "SELECT id, name, category, description, latitude, longitude FROM places ORDER BY name ASC";
$places_result = $conn->query($places_query);
$places = [];
if ($places_result) {
    while ($row = $places_result->fetch_assoc()) {
        $places[] = $row;
    }
}

include_once('../includes/header.php');
?>

<?php include_once('../includes/navbar.php'); ?>

<section class="section" style="min-height: 80vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 1rem;">Explore Our Destinations</h1>
        <p style="text-align: center; color: #6b7280; font-size: 1.1rem; margin-bottom: 3rem;">
            Discover amazing places around the world with interactive maps
        </p>
        
        <?php if (empty($places)): ?>
            <div style="text-align: center; color: #9ca3af; padding: 2rem;">
                <p>No destinations available yet.</p>
            </div>
        <?php else: ?>
            <div class="places-grid">
                <?php foreach ($places as $place): ?>
                    <article class="place-card" role="article">
                        <div class="place-header">
                            <span class="place-category"><?php echo htmlspecialchars($place['category']); ?></span>
                            <h2 class="place-name"><?php echo htmlspecialchars($place['name']); ?></h2>
                        </div>
                        
                        <div class="place-body">
                            <p class="place-description">
                                <?php echo htmlspecialchars($place['description']); ?>
                            </p>
                            
                            <?php if (!empty($place['latitude']) && !empty($place['longitude'])): ?>
                                <a href="https://openstreetmap.org/?mlat=<?php echo htmlspecialchars($place['latitude']); ?>&mlon=<?php echo htmlspecialchars($place['longitude']); ?>&zoom=12" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn btn-primary place-map-link"
                                   aria-label="View <?php echo htmlspecialchars($place['name']); ?> on map">
                                   View on Map →
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>