<?php
/**
 * About Page
 * 
 * Information about ViaNova Travel Explorer
 */

session_start();

include_once('../includes/header.php');
?>

<?php include_once('../includes/navbar.php'); ?>

<section class="about-container">
    <div class="about-section">
        <h1>About ViaNova</h1>
        <p>
            ViaNova is a modern travel exploration platform designed to help you discover and book amazing travel packages around the world. Whether you're seeking adventure in the Sahara Desert, relaxation on Mediterranean beaches, or mountain adventures in the Alps, we have something for every traveler.
        </p>
    </div>
    
    <div class="about-section">
        <h2>Our Mission</h2>
        <p>
            Our mission is to make travel planning simple, accessible, and exciting. We believe that everyone deserves to explore the world and create unforgettable memories. By connecting travelers with carefully curated packages and destinations, we aim to inspire wanderlust and make dream vacations a reality.
        </p>
    </div>
    
    <div class="about-section">
        <h2>What We Offer</h2>
        <p>
            <strong>Curated Travel Packages:</strong> Each package is carefully selected to offer the best value and experience. From luxury retreats to budget-friendly adventures, we have options for every type of traveler.
        </p>
        <p>
            <strong>Destination Exploration:</strong> Use our interactive map to explore destinations around the world. Learn about geography, local culture, and what makes each place special.
        </p>
        <p>
            <strong>Expert Chat Support:</strong> Our AI-powered travel assistant is available 24/7 to answer questions about packages, bookings, and travel tips.
        </p>
    </div>
    
    <div class="about-section">
        <h2>Why Choose Us?</h2>
        <p>
            ✈️ <strong>Expert Curation:</strong> All packages are carefully selected by travel experts who know these destinations inside and out.
        </p>
        <p>
            🌍 <strong>Global Destinations:</strong> We partner with tour operators around the world to bring you authentic experiences.
        </p>
        <p>
            💬 <strong>24/7 Support:</strong> Our chat bot and support team are ready to help you plan your perfect trip.
        </p>
        <p>
            🎯 <strong>Best Prices:</strong> We work directly with local partners to offer competitive pricing and great value.
        </p>
    </div>
    
    <div class="about-section" style="background: linear-gradient(135deg, #0EA5A4 0%, #F97316 100%); color: white; padding: 2rem; border-radius: 1rem; margin-top: 2rem;">
        <h2 style="color: white; margin-bottom: 1rem;">Ready to Start Your Adventure?</h2>
        <p>
            Browse our packages, explore destinations on our map, or chat with our travel assistant to find your perfect trip!
        </p>
        <div style="margin-top: 1rem;">
            <a href="packages.php" class="btn btn-primary" style="display: inline-block;">
                Explore Packages
            </a>
        </div>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>