<?php
/**
 * Packages Page
 * 
 * Displays all travel packages from the database
 * Users can view packages and book if logged in
 * 
 * File Location: /pages/packages.php
 * Database: packages table
 */

session_start();

// Include database connection
// This file is in /pages/, so go up one level (..) to reach /config/db.php
require_once('../config/db.php');

// ============================================
// 1. FETCH ALL PACKAGES FROM DATABASE
// ============================================

$query = "SELECT id, title, description, price, image, duration_days, destination 
          FROM packages 
          ORDER BY id ASC";

$result = $conn->query($query);

// Create empty array to store packages
$packages = array();

// Loop through results and add to array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}

// ============================================
// 2. INCLUDE HEADER AND NAVBAR
// ============================================
// Both files are in /includes/ folder
include_once('../includes/header.php');
include_once('../includes/navbar.php');
?>

<section class="section" style="min-height: 80vh; padding: 3rem 2rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- PAGE HEADER -->
        <h1 style="text-align: center; margin-bottom: 1rem; font-size: 2.5rem; color: #1f2937;">
            Travel Packages
        </h1>
        <p style="text-align: center; color: #6b7280; font-size: 1.1rem; margin-bottom: 3rem;">
            Explore our amazing travel packages and book your next adventure
        </p>

        <!-- ============================================ -->
        <!-- IF NO PACKAGES - SHOW EMPTY MESSAGE -->
        <!-- ============================================ -->
        <?php if (count($packages) == 0): ?>
            
            <div style="text-align: center; background: white; padding: 3rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 1rem;">
                    📦 No travel packages available at the moment
                </p>
                <p style="color: #9ca3af;">Please check back soon for exciting travel deals!</p>
            </div>

        <!-- ============================================ -->
        <!-- DISPLAY ALL PACKAGES IN GRID LAYOUT -->
        <!-- ============================================ -->
        <?php else: ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
                
                <?php foreach ($packages as $package): ?>
                    
                    <!-- SINGLE PACKAGE CARD -->
                    <div style="background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        
                        <!-- PACKAGE IMAGE -->
                        <?php if (!empty($package['image'])): ?>
                            <!-- Image exists in /assets/img/ folder -->
                            <img src="../assets/img/<?php echo htmlspecialchars($package['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($package['title']); ?>"
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <!-- Fallback image if no image uploaded -->
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #0EA5A4 0%, #F97316 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                ✈️
                            </div>
                        <?php endif; ?>
                        
                        <!-- PACKAGE CONTENT -->
                        <div style="padding: 1.5rem;">
                            
                            <!-- Title -->
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.3rem; color: #1f2937;">
                                <?php echo htmlspecialchars($package['title']); ?>
                            </h3>
                            
                            <!-- Price -->
                            <div style="font-size: 1.5rem; color: #0EA5A4; font-weight: bold; margin-bottom: 1rem;">
                                $<?php echo number_format($package['price'], 2); ?>
                            </div>
                            
                            <!-- Destination & Duration Info -->
                            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">
                                <?php if (!empty($package['destination'])): ?>
                                    <p style="margin: 0.3rem 0;">
                                        📍 <?php echo htmlspecialchars($package['destination']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($package['duration_days'])): ?>
                                    <p style="margin: 0.3rem 0;">
                                        📅 <?php echo htmlspecialchars($package['duration_days']); ?> Days
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Description -->
                            <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.5; margin-bottom: 1.5rem;">
                                <?php echo htmlspecialchars($package['description']); ?>
                            </p>
                            
                            <!-- BOOK BUTTON OR LOGIN LINK -->
                            <?php if (isset($_SESSION['user'])): ?>
                                
                                <!-- User IS logged in - Show booking form -->
                                <form method="POST" action="../admin/bookings/create.php">
                                    <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #0EA5A4; color: white; border: none; border-radius: 0.5rem; font-size: 1rem; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                                        📅 Book Now
                                    </button>
                                </form>
                                
                            <?php else: ?>
                                
                                <!-- User is NOT logged in - Show login link -->
                                <a href="login.php" style="display: block; text-align: center; padding: 0.75rem; background: #6b7280; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: bold; transition: background 0.3s;">
                                    🔐 Login to Book
                                </a>
                                
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    
                <?php endforeach; ?>
                
            </div>
            
        <?php endif; ?>

    </div>
</section>

<?php 
// Include footer from /includes/ folder
include_once('../includes/footer.php');
?>