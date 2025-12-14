<?php
session_start();
header('Content-Type: application/json');
require_once('../config/db.php');

$create_table_query = "CREATE TABLE IF NOT EXISTS conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100),
    user_message TEXT NOT NULL,
    bot_response TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create_table_query);

$user_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = json_decode(file_get_contents('php://input'), true);
    if (!empty($json_data['message'])) {
        $user_message = trim($json_data['message']);
    } else if (!empty($_POST['message'])) {
        $user_message = trim($_POST['message']);
    }
}

if (empty($user_message)) {
    echo json_encode(['error' => 'No message provided']);
    exit();
}

$session_id = $_SESSION['conversation_id'] ?? session_id();
$_SESSION['conversation_id'] = $session_id;

$message_lower = strtolower($user_message);
$knowledgeBase = array(
    // ============ GREETINGS ============
    'hello' => 'Hi there! 👋 Welcome to ViaNova Travel Explorer! I\'m your AI assistant here to help you plan your next adventure. What can I help you with today?',
    'hi' => 'Hey! 👋 Welcome to ViaNova! I\'m thrilled to meet you. Whether you\'re looking for destination ideas, booking help, or travel tips, I\'m here for you. How can I assist?',
    'hey' => 'Hey there! 🎉 Welcome to ViaNova Travel Explorer. I\'m your personal travel guide. Ask me about packages, destinations, or anything travel-related!',
    'greetings' => 'Greetings, traveler! 🌍 Welcome to ViaNova. I\'m excited to help you discover amazing places and plan your dream trip. What destination interests you?',
    'good morning' => 'Good morning! ☀️ Welcome to ViaNova! Hope you\'re having a great day. Ready to explore the world? Ask me anything about travel!',
    'good afternoon' => 'Good afternoon! 🌤️ Welcome to ViaNova Travel Explorer. It\'s a perfect time to plan your next adventure! How can I help?',
    'good evening' => 'Good evening! 🌙 Welcome to ViaNova! Even in the evening, it\'s never too late to start dreaming about your next trip. What interests you?',
    'good night' => 'Good night! 🌙 Thanks for visiting ViaNova. If you\'re planning a trip, I\'ll be here whenever you\'re ready. Sweet dreams of travel adventures!',
    'welcome' => 'Welcome to ViaNova Travel Explorer! 🎉 I\'m your AI assistant, and I\'m here to help you find the perfect destination, book amazing packages, and answer all your travel questions. Where would you like to go?',
    'thanks' => 'You\'re very welcome! 😊 I\'m always happy to help. If you have any more questions about travel, destinations, or bookings, just ask away!',
    'thank you' => 'Thank you for choosing ViaNova! 🙏 We appreciate your trust. Need help with anything else? I\'m here 24/7!',
    'appreciate' => 'Thank you so much! 💖 We truly appreciate your interest in ViaNova. Our goal is to make your travel dreams come true. What else can I help with?',
    'how are you' => 'I\'m doing great, thanks for asking! 😄 But more importantly, how are YOU? Ready to plan an amazing trip with ViaNova? Tell me what kind of adventure you\'re dreaming of!',
    'what\'s up' => 'What\'s up, adventurer! 🚀 Just here waiting to help you explore the world. Ask me about destinations, packages, or travel tips. What\'s on your mind?',
    'sup' => 'Sup! 👋 Ready to travel? I can help you find the perfect destination and package. What are you interested in?',
    'yo' => 'Yo! 🎉 Welcome to ViaNova! Let\'s make your travel dreams happen. What destination are you curious about?',
    'help' => 'Of course! I\'m here to help! 🤝 I can assist you with: 📦 Travel Packages | 🎫 Bookings & Reservations | ✈️ Travel Tips & Advice | 🗺️ Destination Information | 🍽️ Restaurants & Dining | 🏛️ Museums & Attractions | 👤 Account & Profile Questions. What would you like to know?',
    'assistance' => 'I\'m here to assist! 🙌 Whether you need info about destinations, want to book a package, need travel tips, or have account questions, I\'ve got you covered. What can I do for you?',
    'support' => 'You\'ve got my full support! 💪 Ask me anything about travel packages, destinations, bookings, travel tips, or account management. I\'m available 24/7. What do you need?',
    'hello there' => 'Hello there! 👋 Great to see you at ViaNova! I\'m your travel companion, ready to help you explore the world. What brings you here today?',
    'pleased to meet you' => 'Pleased to meet you too! 🤝 I\'m ViaNova\'s AI Assistant, and I\'m excited to help you plan an incredible journey. What kind of trip are you dreaming of?',
    'see you later' => 'See you later, adventurer! 👋 Safe travels, and don\'t hesitate to come back when you\'re ready to book your next trip. Goodbye! 🌍',
    'bye' => 'Bye for now! 👋 Safe travels and come back soon to plan your next adventure with ViaNova! 🌍',
    'goodbye' => 'Goodbye! 👋 Thanks for visiting ViaNova. Can\'t wait to help you explore the world soon. Take care! ✈️',
    'take care' => 'You take care too! 😊 Thanks for stopping by ViaNova. Whenever you\'re ready to travel, we\'ll be here. Cheers! 🌍',
    'see you' => 'See you soon! 👋 Come back anytime you want to explore new destinations or book a trip. Bye! 🎉',
    'awesome' => 'Awesome indeed! 🎉 Glad you\'re excited! ViaNova has so much to offer. Want to explore some specific destinations or check out our packages?',
    'nice' => 'Nice! 😊 We appreciate the love! Want to explore more about our amazing destinations and travel packages?',
    'cool' => 'Cool, right?! 😎 ViaNova brings the best travel experiences. Want to dive deeper into any specific destination or package?',
    'great' => 'Great to hear! 🌟 We\'re committed to making your travel experience amazing. What else would you like to know?',
    'love it' => 'We love it too! 💖 That\'s what ViaNova is all about - making travel dreams come true. Ready to book your next adventure?',
    'excited' => 'That\'s the spirit! 🚀 We\'re excited to help you explore the world. What destination are you most excited about?',
    'looking forward' => 'That\'s awesome! We\'re looking forward to helping you create unforgettable memories! 🎯 What can I help you with?',
    // ============ PACKAGES ============
    'package' => 'We offer diverse travel packages to amazing destinations worldwide. Visit our Packages page to explore options!',
    'booking' => 'To book a package: 1) Log in to your account 2) Go to Packages 3) Select a package 4) Click "Book Now"',
    'price' => 'Our packages range from $500 to $5000+ depending on destination and duration. Check individual package prices!',
    'duration' => 'Our packages range from 3-day weekend trips to 30-day world tours. Choose what fits your schedule!',
    'group' => 'We offer group discounts! Contact us for special rates on group bookings.',
    'refund' => 'Cancellations: Full refund if cancelled 30+ days before trip. 50% refund for 15-29 days. No refund within 15 days.',
    'insurance' => 'Travel insurance covers cancellations, medical emergencies, and lost luggage. Ask about our insurance options!',
    
    // ============ ACCOUNT & BOOKING ============
    'login' => 'Log in at /pages/login.php. Demo credentials - Admin: admin@travel.com / admin123 or User: john@example.com / pass123',
    'signup' => 'Create a free account at /pages/signup.php. Takes less than 2 minutes!',
    'password' => 'Forgot your password? Contact our support team through the Contact page or email us.',
    'profile' => 'Update your profile after logging in. You can view your bookings and saved trips there.',
    'payment' => 'We accept credit cards, debit cards, and digital wallets. Payment is secure and encrypted.',
    'confirmation' => 'You\'ll receive a booking confirmation email immediately after booking. Check spam if you don\'t see it!',
    'modify' => 'To modify a booking, log in and go to your profile. Contact support for changes within 48 hours of travel.',
    'cancel' => 'To cancel a booking, visit your profile page and click "Cancel Booking". Refund depends on timing.',
    
    // ============ TRAVEL TIPS ============
    'visa' => 'Visa requirements vary by destination and your nationality. Check your destination country\'s official government website for current requirements.',
    'passport' => 'Ensure your passport is valid for at least 6 months beyond your trip date. Renew it if needed before booking.',
    'packing' => 'Pack light! Bring only essentials. Use packing cubes to organize. Most airlines allow one carry-on + personal item.',
    'budget' => 'Budget $50-150/day for food, $30-200/night for accommodation, plus activities. Research your specific destination.',
    'weather' => 'Check the weather forecast for your destination before packing. Pack appropriate clothing and don\'t forget sunscreen!',
    'safety' => 'Research safety in your destination. Avoid isolated areas at night. Keep copies of important documents.',
    'best time' => 'Spring (April-May) and Fall (Sept-Oct) offer great weather and fewer crowds. Summer is peak season.',
    'flight' => 'Book flights 6-8 weeks in advance for best prices. Tuesday-Thursday flights are usually cheaper.',
    'hotel' => 'Book accommodations through verified sites. Read recent reviews. Contact the hotel directly for potential discounts.',
    'luggage' => 'Bring only what you need. Use a carry-on to avoid baggage fees. Leave room for souvenirs!',
    'transport' => 'Use public transport, taxis, or ride-sharing apps. Download offline maps just in case.',
    'food' => 'Try local cuisine! Eat where locals eat for authentic flavors and better prices. Stay hydrated!',
    'currency' => 'Exchange money at banks for better rates. Use ATMs in your destination. Notify your bank of travel dates.',
    'language' => 'Learn basic phrases in the local language. Locals appreciate the effort! Download a translator app.',
    'documents' => 'Make digital copies of your passport, visa, insurance, and bookings. Keep them in cloud storage.',
    
    // ============ FRANCE - PARIS ============
    'france' => '🇫🇷 FRANCE:\n🏛️ MUST VISIT: Eiffel Tower, Louvre Museum, Notre-Dame Cathedral, Arc de Triomphe, Champs-Élysées, Palace of Versailles, Sacré-Cœur\n🍽️ TOP RESTAURANTS: Le Jules Verne (Eiffel Tower), L\'Astrance (3-star Michelin), Bistrot Paul Bert (traditional French), Café de Flore (historic café)\n🏛️ MUSEUMS: Musée d\'Orsay (Impressionist art), Rodin Museum, Picasso Museum, Centre Pompidou\n✨ BEST TIME: April-May (Spring) or Sept-Oct (Fall)',
    
    'paris' => '🇫🇷 PARIS - City of Light:\n🏛️ MUST VISIT: Eiffel Tower, Louvre Museum, Notre-Dame, Arc de Triomphe, Sacré-Cœur Basilica, Luxembourg Gardens\n🍽️ EAT HERE: Jules Verne (fine dining at Eiffel Tower), L\'Astrance (Michelin 3-star), Ladurée (macarons), Café de Flore (iconic café)\n🏛️ MUSEUMS: Musée d\'Orsay, Picasso Museum, Rodin Museum\n🌙 NIGHTLIFE: Marais district, Pigalle, Latin Quarter bars\n💰 BUDGET: $80-150/day',
    
    // ============ JAPAN - TOKYO ============
    'japan' => '🇯🇵 JAPAN:\n🏯 MUST VISIT: Tokyo Tower, Senso-ji Temple, Shibuya Crossing, Meiji Shrine, Akihabara (tech district), Harajuku (fashion), Mount Fuji\n🍽️ TOP RESTAURANTS: Sukiyabashi Jiro (3-star sushi), Nabezo (wagyu hotpot), Ichiran (ramen), Gonpachi Nishi-Azabu (Japanese izakaya)\n🏛️ MUSEUMS: Tokyo National Museum, teamLab Borderless, Ghibli Museum\n🌸 BEST TIME: March-April (Cherry blossoms) or Oct-Nov (Fall colors)',
    
    'tokyo' => '🇯🇵 TOKYO - Modern & Tradition:\n🏯 MUST VISIT: Senso-ji Temple, Shibuya Crossing (busiest intersection), Meiji Shrine, Tokyo Tower, Tsukiji Outer Market, Akihabara (electronics)\n🍽️ EAT HERE: Sukiyabashi Jiro (legendary sushi, Michelin 3-star), Ichiran (famous ramen), Gonpachi (izakaya), Nabezo (wagyu)\n🏛️ MUSEUMS: Ghibli Museum, teamLab Borderless (digital art), Tokyo National Museum\n🌆 NIGHTLIFE: Shinjuku, Roppongi, Shibuya\n💰 BUDGET: $60-130/day',
    
    // ============ UNITED ARAB EMIRATES - DUBAI ============
    'uae' => '🇦🇪 UNITED ARAB EMIRATES:\n🏙️ MUST VISIT: Burj Khalifa (world\'s tallest), Dubai Mall, Palm Jumeirah, Dubai Fountain, Gold Souk, Al Fahidi Historical District\n🍽️ TOP RESTAURANTS: Nobu Dubai (Japanese), Nusr-et (steakhouse - Salt Bae), Arabian Court (Middle Eastern), Pai Thai (Thai)\n🏛️ MUSEUMS: Dubai Museum, Sheikh Mohammed Centre for Cultural Understanding\n🌞 BEST TIME: October-April (cooler weather)',
    
    'dubai' => '🇦🇪 DUBAI - Luxury Desert:\n🏙️ MUST VISIT: Burj Khalifa (tallest building), Palm Jumeirah (artificial island), Dubai Mall, Dubai Fountain, Gold Souk (souks), Beach resorts\n🍽️ EAT HERE: Nobu (Japanese fine dining), Nusr-et (famous steakhouse), Arabian Court (traditional cuisine), Pai Thai (authentic Thai)\n🏛️ MUSEUMS: Dubai Museum, Sheikh Mohammed Centre for Cultural Understanding\n🏜️ EXPERIENCES: Desert safari, camel riding, dune bashing\n💰 BUDGET: $100-200/day',
    
    // ============ INDONESIA - BALI ============
    'indonesia' => '🇮🇩 INDONESIA:\n🌴 MUST VISIT: Tanah Lot Temple, Ubud Rice Terraces, Sacred Monkey Forest, Gili Islands, Mount Batur (hiking), Seminyak Beach\n🍽️ TOP RESTAURANTS: Mozaic Beach Club (fine dining), Karsa Kafe (traditional Balinese), Bebek Bengil (fried duck), Warung Bodag Barong\n🏛️ TEMPLES: Tanah Lot, Besakih, Ubud Temple\n🌊 BEST TIME: April-October (dry season)',
    
    'bali' => '🇮🇩 BALI - Tropical Paradise:\n🌴 MUST VISIT: Tanah Lot Temple (sea temple), Ubud Rice Terraces, Sacred Monkey Forest Sanctuary, Mount Batur sunrise hike, Gili Islands\n🍽️ EAT HERE: Mozaic Beach Club (fine dining on beach), Karsa Kafe (authentic Balinese), Bebek Bengil (famous fried duck), Warung Bodag (local)\n🏛️ TEMPLES: Tanah Lot, Besakih Mother Temple, Ubud Sacred Monkey Sanctuary\n🏖️ BEACHES: Seminyak, Sanur, Padang Padang\n💰 BUDGET: $30-80/day',
    
    // ============ UNITED STATES - NEW YORK ============
    'usa' => '🇺🇸 USA:\n🗽 MUST VISIT: Statue of Liberty, Times Square, Central Park, Empire State Building, Brooklyn Bridge, American Museum of Natural History\n🍽️ TOP RESTAURANTS: Per Se (French, 3-star Michelin), Eleven Madison Park (American fine dining), Shake Shack (burgers), Joe\'s Pizza (iconic pizza)\n🏛️ MUSEUMS: Met Museum, MoMA, Natural History Museum, American Museum of Natural History\n🌃 BEST TIME: May-June or Sept-Oct',
    
    'newyork' => '🇺🇸 NEW YORK - The City That Never Sleeps:\n🗽 MUST VISIT: Statue of Liberty, Times Square (lights), Central Park, Empire State Building, Brooklyn Bridge, Grand Central Terminal\n🍽️ EAT HERE: Per Se (French 3-star), Eleven Madison Park (American fine dining), Shake Shack (burgers), Joe\'s Pizza (legendary pizza), Katz\'s Deli\n🏛️ MUSEUMS: Metropolitan Museum, MoMA, American Museum of Natural History\n🎭 BROADWAY: Watch world-class theater shows\n💰 BUDGET: $100-200/day',
    
    // ============ UNITED KINGDOM - LONDON ============
    'uk' => '🇬🇧 UNITED KINGDOM:\n🇬🇧 MUST VISIT: Big Ben, Tower of London, Tower Bridge, Buckingham Palace, Westminster Abbey, British Museum\n🍽️ TOP RESTAURANTS: The Ledbury (2-star Michelin), Sketch (fine dining), Fish & Chips shops, Afternoon Tea at The Ritz\n🏛️ MUSEUMS: British Museum (free entry), National Gallery, V&A Museum\n🎭 BEST TIME: June-August',
    
    'london' => '🇬🇧 LONDON - Historic & Modern:\n🏰 MUST VISIT: Big Ben, Tower of London (crown jewels), Tower Bridge, Buckingham Palace, Westminster Abbey, British Museum, Piccadilly Circus\n🍽️ EAT HERE: The Ledbury (2-star Michelin), Sketch (fine dining experience), Fish & Chips shops (Fish House, Poppies), Afternoon Tea at The Ritz\n🏛️ MUSEUMS: British Museum (free!), National Gallery, Victoria & Albert Museum\n🎭 THEATER: West End shows (like Broadway)\n💰 BUDGET: $80-150/day',
    
    // ============ ITALY - ROME & ITALY ============
    'italy' => '🇮🇹 ITALY:\n🏛️ MUST VISIT: Colosseum, Roman Forum, Vatican City, St. Peter\'s Basilica, Pantheon, Trevi Fountain\n🍽️ TOP RESTAURANTS: Aroma (Rooftop, Roman cuisine), Flavio al Velavevodetto (fine dining), Carbonara (traditional pasta), Gelato shops everywhere\n🏛️ CITIES: Rome (history), Venice (canals), Florence (Renaissance art), Amalfi Coast (scenic)\n🌊 BEST TIME: April-May or Sept-Oct',
    
    'rome' => '🇮🇹 ROME - The Eternal City:\n🏛️ MUST VISIT: Colosseum, Roman Forum, Pantheon, Vatican City & St. Peter\'s, Trevi Fountain, Spanish Steps\n🍽️ EAT HERE: Aroma Rooftop (views + cuisine), Flavio al Velavevodetto (traditional), Carbonara (famous pasta dish), Gelato Shops (try Gelateria del Teatro)\n🏛️ MUSEUMS: Vatican Museums, Galleria Borghese\n🎨 ART: Renaissance masterpieces at galleries\n💰 BUDGET: $60-120/day',
    
    'venice' => '🇮🇹 VENICE - City of Canals:\n🚣 MUST VISIT: Grand Canal boat ride, St. Mark\'s Basilica, Doge\'s Palace, Rialto Bridge, gondola rides, Island of Murano (glass)\n🍽️ EAT HERE: Osteria Enoteca Gioggia (seafood), Antiche Carampane (Venice classics), Caffè Florian (historic café)\n🎭 EXPERIENCE: Carnival (February), opera performances\n⛵ ACTIVITIES: Gondola rides, island hopping\n💰 BUDGET: $70-130/day',
    
    'florence' => '🇮🇹 FLORENCE - Renaissance Art:\n🎨 MUST VISIT: Uffizi Gallery (world\'s best Renaissance art), Duomo Cathedral, Ponte Vecchio (bridge), Accademia Gallery (David statue)\n🍽️ EAT HERE: Osteria Vini e Olii (traditional Tuscan), Trattoria Mario (famous), Gelateria Badiani (best gelato)\n🍷 WINE: Tuscan wine tours in nearby countryside\n🚶 WALKS: Arno riverbank, city panorama from Piazzale Michelangelo\n💰 BUDGET: $50-100/day',
    
    // ============ GREECE ============
    'greece' => '🇬🇷 GREECE:\n🏖️ MUST VISIT: Santorini (sunsets), Mykonos (beaches), Athens Acropolis, Delphi, Crete, Greek islands\n🍽️ TOP RESTAURANTS: Canaves Oia Suites (sunset dining Santorini), Nikolas Taverna (traditional Greek), Souvlaki shops, Saganaki (fried cheese)\n🏛️ MUSEUMS: Acropolis Museum, Archaeological museums\n⛱️ BEST TIME: June-September',
    
    'santorini' => '🇬🇷 SANTORINI - Greek Island Paradise:\n🌅 MUST VISIT: Oia village (sunset views), Fira town, Red Beach, Akrotiri ancient ruins, caldera views, whitewashed buildings\n🍽️ EAT HERE: Canaves Oia Suites (romantic sunset dinner), Nikolas Taverna (traditional Greek), Fava dip (chickpea puree), fresh seafood\n🍷 WINE: Volcanic wine tastings (unique local wines)\n⛱️ BEACHES: Red Beach, Kamari Beach\n💰 BUDGET: $80-150/day',
    
    'athens' => '🇬🇷 ATHENS - Ancient Wonder:\n🏛️ MUST VISIT: Acropolis & Parthenon, Acropolis Museum, Roman Agora, Panathenaic Stadium, Plaka district (old town)\n🍽️ EAT HERE: Varoulko Seaside (fine dining), Ta Kagia (traditional Greek), Souvlaki stands, Saganaki (fried feta cheese)\n🏛️ MUSEUMS: Acropolis Museum (modern architecture), National Museum of Greece\n🌙 NIGHTLIFE: Plaka district, rooftop bars\n💰 BUDGET: $50-100/day',
    
    // ============ EGYPT ============
    'egypt' => '🇪🇬 EGYPT:\n🔺 MUST VISIT: Pyramids of Giza, The Sphinx, Luxor Temples, Valley of the Kings, Egyptian Museum, Nile River cruise\n🍽️ TOP RESTAURANTS: Nile-view restaurants, Koshary (popular dish), Fuul (fava bean breakfast), Fresh dates & honey\n🏛️ MUSEUMS: Egyptian Museum (Cairo), Luxor Museum\n🌞 BEST TIME: October-April (cooler)',
    
    'cairo' => '🇪🇬 CAIRO - Ancient Capital:\n🔺 MUST VISIT: Pyramids of Giza (must see!), The Sphinx, Egyptian Museum (mummies & treasures), Khan el-Khalili Bazaar (souvenirs)\n🍽️ EAT HERE: Nile-side restaurants (sunset views), Koshary (lentils+pasta - iconic), Fuul (fava bean breakfast), Fresh juice stands\n🏛️ MUSEUMS: Egyptian Museum (world\'s largest Egyptian collection), Citadel of Saladin\n📜 HISTORY: See 5,000 years of history!\n💰 BUDGET: $40-80/day',
    
    'luxor' => '🇪🇬 LUXOR - Ancient Thebes:\n🏛️ MUST VISIT: Valley of the Kings (pharaoh tombs), Karnak Temple (massive complex), Luxor Temple, Valley of Queens\n🍽️ EAT HERE: Nile cruise dining, local fish restaurants, Egyptian mezze platters\n⛵ ACTIVITIES: Nile River cruise (2-4 days), balloon ride over Valley\n🌅 SUNSET: Amazing sunsets over the Nile\n💰 BUDGET: $50-100/day',
    
    // ============ THAILAND ============
    'thailand' => '🇹🇭 THAILAND:\n🏯 MUST VISIT: Grand Palace (Bangkok), Wat Arun (Temple of Dawn), Phuket beaches, Chiang Mai temples, Krabi, Phi Phi Islands\n🍽️ TOP RESTAURANTS: Nahm (Thai fine dining, 1-star Michelin), Street pad thai (best & cheapest), Mango sticky rice, Tom Yum soup\n🏛️ TEMPLES: Grand Palace, Wat Phra Kaew, Wat Saket\n🌞 BEST TIME: November-February (cool & dry)',
    
    'bangkok' => '🇹🇭 BANGKOK - City of Angels:\n🏯 MUST VISIT: Grand Palace (stunning!), Wat Arun (Temple of Dawn), Wat Pho (reclining Buddha), Floating Markets, Chinatown, SkyTrain views\n🍽️ EAT HERE: Street pad thai (best & cheapest!), Nahm (1-star Michelin), Tom Yum soup, Mango sticky rice, Khao Soi (curry noodles)\n🏛️ TEMPLES: Grand Palace, Wat Pho, Wat Saket, Wat Benchamabophit\n🌆 NIGHTLIFE: Rooftop bars, night markets, Thai massage\n💰 BUDGET: $30-60/day',
    
    'phuket' => '🇹🇭 PHUKET - Beach Paradise:\n🏖️ MUST VISIT: Patong Beach, Phi Phi Islands (Maya Bay), Phang Nga Bay (limestone karsts), Big Buddha statue, Old Phuket Town\n🍽️ EAT HERE: Seafood restaurants on beach, Fresh fish, Thai curries, Satay sticks\n⛱️ BEACHES: Patong (crowded fun), Karon (family-friendly), Kata (quieter)\n🏝️ ACTIVITIES: Island hopping, snorkeling, diving, speedboat tours\n💰 BUDGET: $40-80/day',
    
    // ============ VIANOVA INFO ============
    'about' => 'ViaNova is your trusted travel companion! We help millions explore the world with curated packages and expert guidance.',
    'contact' => 'Contact us through /pages/contact.php or email support@vianova.com. We respond within 24 hours!',
    'map' => 'Explore our interactive world map at /pages/map.php to see all our amazing destinations!',
    'support' => 'Need help? Visit our Contact page, use the Chat Bot (that\'s me!), or email support@vianova.com',
    'company' => 'ViaNova has been helping travelers since 2015. We\'ve booked over 1 million trips worldwide!',
    'team' => 'Our team includes travel experts, guides, and customer service specialists. We\'re here to help!',
    'reviews' => 'Check out customer reviews on our website. 98% satisfaction rate from verified travelers!',
    'social' => 'Follow ViaNova on Instagram, Facebook, and Twitter for travel inspiration and exclusive deals!',
    
    // ============ GENERAL ============
    'hello' => 'Hi there! 👋 Welcome to ViaNova. I\'m your travel Q&A assistant. How can I help you today?',
    'hi' => 'Hey! 👋 I\'m ViaNova\'s AI assistant. Ask me about packages, travel tips, restaurants, museums, or places to visit!',
    'help' => 'I can help with: 📦 Packages | 🎫 Bookings | ✈️ Travel tips | 🗺️ Destinations | 🍽️ Restaurants | 🏛️ Museums | 👤 Account questions',
    'thanks' => 'You\'re welcome! Happy to help. Anything else you\'d like to know? 😊',
    'bye' => 'Safe travels! 🌍 Come back soon for your next adventure with ViaNova!',
    'time' => 'I\'m available 24/7 to help! But for urgent issues, contact support@vianova.com',
    
    // ============ DEFAULT ============
    'default' => 'Great question! I don\'t have specific info on that topic. Try asking about destinations like Paris, Tokyo, Dubai, Rome, Athens, Bangkok, or others. Or contact our support team! 📧'
);
$reply = $knowledgeBase['default'];

foreach ($knowledgeBase as $keyword => $response) {
    if (strpos($message_lower, $keyword) !== false) {
        $reply = $response;
        break;
    }
}

$insert_query = "INSERT INTO conversations (session_id, user_message, bot_response) VALUES (
    '" . $conn->real_escape_string($session_id) . "',
    '" . $conn->real_escape_string($user_message) . "',
    '" . $conn->real_escape_string($reply) . "'
)";

$conn->query($insert_query);

echo json_encode([
    'reply' => $reply,
    'status' => 'success'
]);
exit();
?>