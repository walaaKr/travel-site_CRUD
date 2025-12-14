-- Add 14 Travel Packages to ViaNova
-- Run this in phpMyAdmin or MySQL

USE travel_explorer;

-- Clear existing packages (optional - remove this line to keep existing)
-- DELETE FROM packages;

-- Insert 14 diverse travel packages
INSERT INTO packages (title, description, price, image, duration_days, destination) VALUES

-- Adventure Packages
('Sahara Desert Adventure', 'Experience the golden dunes of Morocco\'s Sahara. Camel trekking, stargazing, and Bedouin camps await. Includes guided tours, traditional meals, and desert camping under the stars.', 899.99, 'sahara.jpg', 7, 'Morocco'),

('Amazon Rainforest Expedition', 'Explore the world\'s largest rainforest. Wildlife spotting, river cruises, and indigenous village visits. See exotic animals, rare birds, and experience the jungle like never before.', 1299.99, 'amazon.jpg', 10, 'Brazil'),

('African Safari Experience', 'Witness the Big Five in their natural habitat. Game drives, luxury lodges, and unforgettable sunsets over the savanna. Includes Serengeti and Ngorongoro Crater visits.', 2499.99, 'safari.jpg', 8, 'Tanzania'),

-- Beach & Relaxation
('Mediterranean Coast Escape', 'Relax on pristine beaches of Greece and Croatia. Crystal-clear waters, charming coastal villages, and Mediterranean cuisine. Island hopping included.', 1199.99, 'mediterranean.jpg', 10, 'Greece'),

('Maldives Paradise Retreat', 'Ultimate luxury in overwater villas. Pristine beaches, world-class diving, and spa treatments. All-inclusive resort experience in the Indian Ocean.', 3499.99, 'maldives.jpg', 7, 'Maldives'),

('Bali Tropical Getaway', 'Discover the Island of Gods. Ancient temples, rice terraces, yoga retreats, and stunning beaches. Perfect blend of culture, nature, and relaxation.', 1099.99, 'bali.jpg', 9, 'Indonesia'),

-- Mountain & Nature
('Alpine Mountain Retreat', 'Hiking, skiing, and Alpine lodge stays in the Swiss Alps. Perfect for nature lovers and adventure seekers. Includes scenic train rides and mountain excursions.', 1499.99, 'alps.jpg', 8, 'Switzerland'),

('Patagonia Wilderness Trek', 'Explore the end of the world. Glaciers, mountains, and pristine wilderness. Guided trekking through Torres del Paine and Perito Moreno Glacier visits.', 1899.99, 'patagonia.jpg', 12, 'Argentina'),

('Iceland Northern Lights', 'Chase the Aurora Borealis. Geysers, waterfalls, hot springs, and volcanic landscapes. Winter wonderland adventure with glacier hiking and ice caves.', 1699.99, 'iceland.jpg', 6, 'Iceland'),

-- Cultural & City Tours
('Japan Cultural Journey', 'From Tokyo\'s neon lights to Kyoto\'s ancient temples. Experience samurai history, geisha culture, cherry blossoms, and world-famous cuisine.', 2299.99, 'japan.jpg', 14, 'Japan'),

('Italian Grand Tour', 'Rome, Florence, and Venice in one epic journey. Art, history, and incredible food. Colosseum, Vatican, Uffizi Gallery, and gondola rides included.', 1799.99, 'italy.jpg', 12, 'Italy'),

('Egyptian Wonders', 'Pyramids, Sphinx, and Nile cruises. Walk in the footsteps of pharaohs. Includes Valley of the Kings, Luxor temples, and Cairo\'s bustling markets.', 1399.99, 'egypt.jpg', 9, 'Egypt'),

-- Unique Experiences
('New Zealand Adventure', 'Lord of the Rings landscapes come alive. Bungee jumping, hiking, glowworm caves, and Maori culture. North and South Island exploration.', 2199.99, 'newzealand.jpg', 15, 'New Zealand'),

('Dubai Luxury Experience', 'World\'s tallest buildings, desert safaris, and gold souks. Ultra-modern luxury meets Arabian tradition. Burj Khalifa, Palm Jumeirah, and dune bashing.', 1599.99, 'dubai.jpg', 5, 'UAE');

-- Verify insertion
SELECT id, title, price, destination FROM packages ORDER BY id;
